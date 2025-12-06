<?php

namespace App\Models;

use App\Enums\Visibility;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasUuids;

    protected $fillable = [
        'uploader_id',
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'visibility',
    ];

    protected $casts = [
        'size' => 'integer',
        'visibility' => Visibility::class,
    ];

    protected $with = [
        'uploader',
    ];

    /**
     * Boot model events
     */
    protected static function booted()
    {
        static::deleting(function (File $file) {
            if ($file->file_path && Storage::disk($file->visibility)->exists($file->file_path)) {
                Storage::disk($file->visibility)->delete($file->file_path);
            }
        });
    }

    // File diupload oleh User
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}

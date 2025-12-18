<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyMood extends Model
{
    use HasUuids;

    protected $fillable = [
        'student_id',
        'emotion_name',
        'magnitude',
        'story',
        'is_custom',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_custom' => 'bool',
        ];
    }

    public static function getNegativeEmotions()
    {
        return ['Sedih', 'Marah', 'Takut'];
    }

    /**
     * Relasi: Request dimiliki oleh satu Student (siswa).
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RequestCounseling extends Model
{
    use HasUuids;

    protected $fillable = [
        'student_id',
        'title',
        'description',
        'urgency',
        'status',
    ];

    /**
     * Relasi: Request dimiliki oleh satu Student (siswa).
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi: Setiap Request hanya memiliki satu Schedule (atau belum ada).
     */
    public function schedule(): HasOne
    {
        return $this->hasOne(ScheduleCounseling::class, 'request_id');
    }
}

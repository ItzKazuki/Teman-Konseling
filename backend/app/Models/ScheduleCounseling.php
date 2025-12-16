<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleCounseling extends Model
{
    use HasUuids;

    protected $fillable = [
        'request_id',
        'counselor_id',
        'method',
        'schedule_date',
        'time_slot',
        'status',
        'confirmed_at',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'time_slot' => 'datetime:H:i',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Relasi: Schedule terkait dengan satu Request.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(RequestCounseling::class. 'request_id');
    }

    /**
     * Relasi: Schedule terkait dengan satu Counselor.
     */
    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }
}

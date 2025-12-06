<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'student_id',
        'mood_category',
        'mood_level',
        'note',
        'recorded_at',
    ];
}

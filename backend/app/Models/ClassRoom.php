<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'name',
        'description',
        'homeroom_teacher',
    ];
}

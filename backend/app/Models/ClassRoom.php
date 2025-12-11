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

    public function homeroomTeacher()
    {
        // Asumsi homeroom_teacher di tabel class_rooms merujuk ke users.id
        return $this->belongsTo(User::class, 'homeroom_teacher');
    }

    public function students()
    {
        // Relasi ini sudah benar
        return $this->hasMany(Student::class, 'class_room_id', 'id');
    }
}

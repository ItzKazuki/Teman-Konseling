<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Student extends Authenticatable implements CanResetPasswordContract
{
    use CanResetPassword;
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'nis',
        'nisn',
        'name',
        'class_room_id',
        'email',
        'phone_number',
        'password',
        'avatar_path',
        'postal_code',
        'address',
        'village',
        'district',
        'city',
        'province',
        'parent_name',
        'parent_phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'avatar_path',
    ];

    protected $appends = [
        'avatar_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            return Storage::url($this->avatar_path);
        }

        return asset('static/profile.png');
    }

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    public function dailyMoods()
    {
        return $this->hasMany(DailyMood::class);
    }
}

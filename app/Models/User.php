<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'reset_token',
        'phone',
        'country',
        'skills',
        'about',
        'social_media',
        'avatar',
    ];
    protected $hidden = [
        'password',
        'reset_token',
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cvProjects(): HasMany
    {
        return $this->hasMany(CvProject::class);
    }
}

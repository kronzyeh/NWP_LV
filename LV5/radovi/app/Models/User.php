<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Task;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Dodaj role u fillable
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $attributes = [
        'role' => 'student', // Postavi defaultnu rolu
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}


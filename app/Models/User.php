<?php

namespace App\Models; // ✅ Fixed the spelling here

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // ✅ Removed 'HasApiTokens' to stop the error

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- RELATIONSHIPS ---

    // A student has many results
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // A teacher has many quizzes
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'teacher_id');
    }
}
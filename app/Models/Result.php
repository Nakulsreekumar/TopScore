<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    // ✅ This fixes the MassAssignmentException and allows tab_switches to save
    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'total_marks',
        'grade',
        'tab_switches' // <-- Added this!
    ];

    // Relationship: A result belongs to a User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relationship: A result belongs to a Quiz
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
    
}
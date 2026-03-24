<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'duration_minutes', 
        'unique_code', 
        'teacher_id',
        'start_time', 
        'end_time'
    ];

    // This ensures Laravel treats these columns as Carbon datetime objects
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function results() {
        return $this->hasMany(Result::class);
    }
    // In App\Models\Quiz.php

// Get the teacher who originally created this
public function originalCreator() {
    return $this->belongsTo(Quiz::class, 'parent_id');
}

// Get all the clones made from this quiz
public function clones() {
    return $this->hasMany(Quiz::class, 'parent_id');
}
}
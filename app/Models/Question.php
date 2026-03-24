<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $question_text
 * @property array $options
 * @property string $correct_answer
 * @property int $marks
 * @property int $quiz_id
 */
class Question extends Model 
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'options' => 'array'
    ]; 

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
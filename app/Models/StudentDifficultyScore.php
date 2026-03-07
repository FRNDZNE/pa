<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDifficultyScore extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'total_questions'  => 'integer',
        'correct_answers'  => 'integer',
        'score_percentage' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}

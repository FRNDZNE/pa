<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';
    protected $guarded = [];

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
    
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function studentScore()
    {
        return $this->hasMany(StudentScore::class);
    }

    public function studentDifficultyScores()
    {
        return $this->hasMany(StudentDifficultyScore::class);
    }

    public function questions()
    {
        return $this->hasManyThrough(Question::class, Material::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }


}

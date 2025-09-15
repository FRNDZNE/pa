<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';
    protected $guarded = [];


    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function studentScore()
    {
        return $this->hasMany(StudentScore::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }


}

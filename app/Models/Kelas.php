<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'kelas'; // Assuming the table name is 'kelas'

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function lesson()
    {
        return $this->hasMany(Lesson::class);
    }
}

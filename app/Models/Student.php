<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = 'students'; // Assuming the table name is 'students'
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentAnswer()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function studentScore()
    {
        return $this->hasMany(StudentScore::class);
    }
}

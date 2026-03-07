<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}

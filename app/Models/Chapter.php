<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['subject_id', 'name', 'position'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function mcqs()
    {
        return $this->hasMany(Mcq::class);
    }

    public function shortQuestions()
    {
        return $this->hasMany(ShortQuestion::class);
    }

    public function cqs()
    {
        return $this->hasMany(Cq::class);
    }
}

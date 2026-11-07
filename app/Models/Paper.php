<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $fillable = [
        'user_id', 'title', 'school_name', 'exam_name', 'exam_time', 'full_marks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mcqs()
    {
        return $this->belongsToMany(Mcq::class, 'paper_mcqs')
            ->withPivot('position')->withTimestamps()->orderBy('paper_mcqs.position');
    }

    public function shortQuestions()
    {
        return $this->belongsToMany(ShortQuestion::class, 'paper_shorts')
            ->withPivot('position')->withTimestamps()->orderBy('paper_shorts.position');
    }

    public function cqs()
    {
        return $this->belongsToMany(Cq::class, 'paper_cqs')
            ->withPivot('position')->withTimestamps()->orderBy('paper_cqs.position');
    }
}

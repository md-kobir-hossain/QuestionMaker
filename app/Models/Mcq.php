<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mcq extends Model
{
    protected $fillable = [
        'chapter_id', 'question', 'option_a', 'option_b', 'option_c',
        'option_d', 'correct_answer', 'marks', 'source_image', 'created_by',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

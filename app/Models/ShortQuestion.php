<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortQuestion extends Model
{
    protected $fillable = [
        'chapter_id', 'question', 'marks', 'source_image', 'created_by',
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

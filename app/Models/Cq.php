<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cq extends Model
{
    protected $fillable = ['chapter_id', 'stem', 'source_image', 'created_by'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parts()
    {
        return $this->hasMany(CqPart::class)->orderBy('position');
    }

    public function getTotalMarksAttribute(): int
    {
        return (int) $this->parts->sum('marks');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['class_id', 'name', 'cq_pattern'];

    protected $casts = [
        'cq_pattern' => 'array',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('position');
    }
}

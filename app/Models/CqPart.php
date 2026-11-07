<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CqPart extends Model
{
    protected $fillable = ['cq_id', 'label', 'question', 'marks', 'position'];

    public function cq()
    {
        return $this->belongsTo(Cq::class);
    }
}

<?php

namespace App;

use App\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'faved_id',
        'faved_type',
    ];

    public function comment()
    {
        return $this->belongsTo(\App\Comment::class);
    }

    public function faved()
    {
        return $this->morphTo();
    }

    //actionable target
    public function target() {
        return $this->morphTo();
    }
}

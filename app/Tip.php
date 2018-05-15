<?php

namespace App;

use App\Model;

class Tip extends Model
{
    public $fillable = [
        'user_id',
        'tipable_id',
        'tipable_type',
        'amount',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function tipable()
    {
        return $this->morphTo();
    }

    //actionable target
    public function target()
    {
        return $this->morphTo();
    }
}

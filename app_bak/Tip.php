<?php

namespace App;

use App\Model;

class Tip extends Model
{

    protected $fillable = [
        'user_id',
        'amount',
        'tipable_type',
        'tipable_id',
    ];

    public function tipable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}

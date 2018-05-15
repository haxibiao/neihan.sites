<?php

namespace App;

use App\Model;

class Follow extends Model
{
    public $fillable = [
        'user_id',
        'followed_type',
        'followed_id',
    ];

    public function followed()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}

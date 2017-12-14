<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = [
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

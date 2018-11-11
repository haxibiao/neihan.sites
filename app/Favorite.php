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

    public function article()
    {
        return $this->belongsTo(\App\Article::class, 'faved_id');
    }

    public function video()
    {
        return $this->belongsTo(\App\Video::class, 'faved_id');
    }

    public function user()
    {
        $this->belongsTo(\App\User::class, 'faved_id');
    }

    //actionable target
    public function target() {
        return $this->morphTo();
    }
}

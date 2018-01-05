<?php

namespace App;

use App\Model;

class Collection extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'type',
        'name',
        'logo',
    ];

    public function articles()
    {
        return $this->belongsToMany(\App\Article::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function favorites()
    {
        return $this->morphMany(\App\Favorite::class, 'faved');
    }

    //computed methods

    public function has_logo()
    {
        return $this->logo ? $this->logo : "/images/category_09.png";
    }

}

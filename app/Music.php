<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $fillable = [
        'user_id',
        'article_id',
        'path',
        'title',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}

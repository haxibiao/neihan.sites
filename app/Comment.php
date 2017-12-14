<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = [
        'user_id',
        'body',
        'comment_id',
        'commentable_type',
        'commentable_id',
    ];

    public function commented()
    {
        return $this->belongsTo(\App\Comment::class);
    }

    public function replyComments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
    public function commentable()
    {
        return $this->morpTo();
    }
}

<?php

namespace App;

use App\Model;

class Comment extends Model
{
    protected $touches = ['commentable'];

    public $fillable = [
        'user_id',
        'body',
        'comment_id',
        'commentable_id',
        'commentable_type',
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

    public function atUser()
    {
        return $this->belongsTo(\App\User::class, 'at_uid');
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}

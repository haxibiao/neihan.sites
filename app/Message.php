<?php

namespace App;

use App\Model;

class Message extends Model
{
    protected $touches = ['chat'];

    public $fillable = [
        'user_id',
        'chat_id',
        'message',
    ];

    public function chat()
    {
        return $this->belongsTo(\App\Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}

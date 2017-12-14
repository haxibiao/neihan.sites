<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}

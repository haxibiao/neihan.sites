<?php

namespace App;

use App\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'from_user_id',
        'to_user_id',
        'relate_id',
        'type',
        'log',
        'amount',
        'status',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function toUser()
    {
        return $this->belongsTo(\App\User::class, 'to_user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(\App\User::class, 'from_user_id');
    }

    public function tip()
    {
        return $this->belongsTo(\App\Tip::class, 'relate_id');
    }
}

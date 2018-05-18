<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
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
}

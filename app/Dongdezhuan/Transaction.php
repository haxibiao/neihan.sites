<?php

namespace App\Dongdezhuan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'wallet_id',
        'user_id',
        'from_user_id',
        'to_user_id',
        'relate_id',
        'type',
        'remark',
        'log',
        'amount',
        'status',
        'balance',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(\App\Dongdezhuan\User::class);
    }

    public function toUser():BelongsTo
    {
        return $this->belongsTo(\App\Dongdezhuan\User::class, 'to_user_id');
    }

    public function fromUser():BelongsTo
    {
        return $this->belongsTo(\App\Dongdezhuan\User::class, 'from_user_id');
    }

}

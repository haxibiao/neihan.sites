<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gold extends Model
{

    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'user_id',
        'gold',
        'wallet_id',
        'balance',
        'remark',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }
}

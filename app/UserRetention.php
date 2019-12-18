<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRetention extends Model
{
    const USERRETENTION_CACHE_KEY = 'user_retention_to_user_%s';

    protected $fillable = [
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(\App\User::class);
    }
}

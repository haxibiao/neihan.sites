<?php

namespace App;

use App\User;
use Auth;
use App\Model;

class Chat extends Model
{
    protected $fillable = [
        'uids',
    ];

    public function messages()
    {
        return $this->hasMany(\App\Message::class);
    }

    public function users()
    {
        return $this->belongsToMany(\App\User::class);
    }

    //计算用方法将uid中的json转换,并算出with_id,最后query出wiht_id的信息并全部返回
    public function withUser()
    {
        $uids    = json_decode($this->uids);
        $with_id = array_sum($uids) - Auth::id();
        return User::find($with_id);
    }
}

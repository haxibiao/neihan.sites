<?php

namespace App;

use App\Model;
use Auth;

class Chat extends Model
{
    public $fillable = [
        'uids',
    ];

    public function messages()
    {
        return $this->hasMany(\App\Message::class);
    }

    public function users()
    {
        return $this->belongsToMany(\App\User::class)->withPivot('unreads');
    }

    //computed methods..
    public function withUser()
    {
        $uids        = json_decode($this->uids);
        $current_uid = Auth::id() ? Auth::id() : (session('user') ? session('user')->id : 0);
        $with_id     = array_sum($uids) - $current_uid;
        return User::find($with_id);
    }
}

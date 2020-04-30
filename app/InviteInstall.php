<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InviteInstall extends Model
{
    public $fillable = [
        'user_id',
        'user_agent',
        'ip',
        'os',
        'os_version',
    ];

    //被邀请安装成功的用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

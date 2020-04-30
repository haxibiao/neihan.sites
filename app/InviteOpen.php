<?php

namespace App;

use App\Model;

class InviteOpen extends Model
{
    protected $fillable = [
        'user_id',
        'user_agent',
        'ip',
        'os',
        'os_version',
        'os_build',
        'model',
        'installs',
    ];

    const INSTALLED = 1;
    const UNINSTALL = 0;

    //邀请别人的上级用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeInstalled($query)
    {
        return $query->where('installs', InviteOpen::INSTALLED);
    }

}

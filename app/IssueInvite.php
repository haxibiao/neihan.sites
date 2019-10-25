<?php

namespace App;


class IssueInvite extends Model
{
    public $fillable = [
        'user_id',
        'issue_id',
        'invite_user_id',
    ];

    public function freshTimestamp()
    {
        return time();
    }
}

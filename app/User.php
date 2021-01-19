<?php

namespace App;

use Haxibiao\Breeze\User as BreezeUser;

class User extends BreezeUser
{

    protected $with = ['hasOneProfile'];

    public static function boot()
    {
        parent::boot();
        self::saving(function ($user) {
            if ($user->isDirty(['name'])) {
                $user->name = app('SensitiveUtils')->replace($user->name, '*');
            }
            if (empty($user->api_token)) {
                $user->api_token = str_random(60);
            }
        });
    }

}

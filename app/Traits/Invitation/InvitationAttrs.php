<?php

namespace App\Traits;

use App\User;

trait InvitationAttrs
{
    public function getTitleAttribute()
    {
        // $title = config('app.name_cn');
        // $app   = $this->app;
        // if (!is_null($app)) {
        //     $title = $app->name;
        // }

        // $title .= '厂';

        // return $title;
    }

    public function getSecondaryUsersAttribute()
    {
        return User::find($this->secondary_user_ids);
    }

    public function getTotalContributeAttribute()
    {
        return $this->total_contribute ?? 0;
    }
}

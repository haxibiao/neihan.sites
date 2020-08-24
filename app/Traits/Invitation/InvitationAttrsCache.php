<?php

namespace App\Traits;

use App\User;

trait InvitationAttrsCache
{
    public function getTitleCache()
    {
        // $title = config('app.name_cn');
        // $app   = $this->app;
        // if (!is_null($app)) {
        //     $title = $app->name;
        // }

        // $title .= '厂';

        // return $title;
    }

    public function getSecondaryUsersCache()
    {
        return User::find($this->secondary_user_ids);
    }

    public function getTotalContributeCache()
    {
        return $this->total_contribute ?? 0;
    }
}

<?php

namespace App\Traits;

use App\User;
use Carbon\Carbon;

trait TaskMethod
{

    //检查发布数
    public function publicArticleTask(): bool
    {
        $user = getUser();
        //包含软删除
        $count_article = $user->articles()->withTrashed()->whereDate('created_at', Carbon::today())->count();

        if ($count_article >= $this->resolve['limit']) {
            return true;
        }

        return false;
    }

    public function publicArticleTaskCount($user)
    {
        return $user->articles()->withTrashed()->whereDate('created_at', Carbon::today())->count();
    }

    public function publicArticleTaskCountArgs()
    {
        return [checkUser()];
    }

    public function checkUserIsUpdateAvatar(): bool
    {

        $user             = $this->getCurrentUser();
        $avatar_formatter = 'storage/avatar/avatar-%d.jpg';
        for ($i = 1; $i <= 15; $i++) {
            if (str_contains($user->avatar, sprintf($avatar_formatter, $i))) {
                return false;
            }
        }

        return true;
    }

    public function checkUserIsUpdatePassAndPhone(): bool
    {
        $user = $this->getCurrentUser();

        if (is_null($user->phone) || empty($user->phone)) {

            return false;
        }
        return true;
    }

    public function checkUserIsUpdateGenderAndBirthday(): bool
    {
        $user    = $this->getCurrentUser();
        $profile = $user->profile;
        if ($profile->gender == -1 || is_null($profile->birthday)) {
            return false;
        }
        return true;
    }

    public function getCurrentUser(): User
    {
//            避免重复查询数据库
        if (is_null($this->user)) {
            $user       = checkUser();
            $this->user = $user;
            return $this->user;
        }
        return $this->user;
    }
}

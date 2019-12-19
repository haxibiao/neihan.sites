<?php

namespace App\Traits;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait TaskMethod
{

    //检查发布数
    public function publicArticleTask(): bool
    {
        $user = checkUser();
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
        if(Str::contains($user->avatar,'storage/avatar/avatar')){
            return false;
        }

        return true;
    }

    public function checkUserIsUpdatePassAndPhone(): bool
    {
        $user = $this->getCurrentUser();

        if (is_null($user->phone) && is_null($user->password)) {
            return false;
        }
        return true;
    }

    public function checkUserIsUpdateGenderAndBirthday(): bool
    {
        $user    = $this->getCurrentUser();
        $profile = $user->profile;
        if (is_null($profile->gender) || is_null($profile->birthday)) {
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

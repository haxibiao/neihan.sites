<?php

namespace App\Traits;

trait CategoryAttrs
{
    public function getCanEditAttribute()
    {
        if ($user_id = getUserId()) {
            return $this->user_id == $user_id;
        }
        return false;
    }

    public function getLogoUrlAttribute()
    {
        $path = empty($this->logo) ? '/img/category.logo.jpg' : parse_url($this->logo, PHP_URL_PATH);

        return cdnurl($path);
    }

    public function getIconUrlAttribute()
    {
        return str_replace('.logo.jpg', '.logo.small.jpg', $this->logoUrl);
    }

    public function getFollowIdAttribute()
    {
        if ($user = getUser(false)) {
            $follow = $user->followings()->where('followed_type', 'categories')->where('followed_id', $this->id)->first();
            return $follow ? $follow->id : 0;
        }
        return 0;
    }

    public function getFollowedAttribute()
    {
        return $this->follow_id ? 1 : 0;
    }

    public function getTopAdminsAttribute()
    {
        return $this->admins()->take(9)->get();
    }

    public function getTopAuthorsAttribute()
    {
        return $this->authors()->take(9)->get();
    }
}

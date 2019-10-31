<?php

namespace App\Traits;

use App\Category;
use Illuminate\Support\Facades\Auth;

trait CategoryAttrs
{
    public function canAdmin()
    {
        if (!Auth::check()) {
            return false;
        }
        $is_category_admin = in_array(Auth::id(), $this->admins->pluck('id')->toArray());
        return $is_category_admin || Auth::user()->checkEditor();
    }

    public function isFollowed()
    {
        return Auth::check() && Auth::user()->isFollow('categories', $this->id);
    }

    public function logo_app()
    {
        return cdnurl($this->logo_app);
    }

    public function checkAdmin()
    {
        return Auth::check() && $this->isAdmin(Auth::user());
    }

    public function isAdmin($user)
    {
        if ($this->admins()->where('users.id', $user->id)->count() || $this->user_id == $user->id) {
            return true;
        }
        return false;
    }

    public function description()
    {
        return empty($this->description) ? '这专题管理很懒，一点介绍都没留下...' : $this->description;
    }

    public function link()
    {
        return '<a href="/category/' . $this->id . '">' . $this->name . '</a>';
    }

    public function getCanEditAttribute()
    {
        if ($user_id = getUserId()) {
            return $this->user_id == $user_id;
        }
        return false;
    }

    public function getLogoUrlAttribute()
    {
        $path = parse_url($this->logo, PHP_URL_PATH);
        return !empty($path) ? cdnurl($path) : url(Category::LOGO_PATH);
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

<?php

namespace App;

use App\Traits\TimeAgo;
use App\Traits\UserRelation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use Notifiable, TimeAgo, UserRelation;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'qq',
        'introduction',
        'is_editor',
        'is_seoer',
        'seo_meta',
        'seo_push',
        'seo_tj',
        'api_token',
        'gender',
        'introduction_tips',
        'is_tips',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function avatar()
    {
        //读取qq头像
        if (empty($this->avatar)) {
            $qq = $this->qq;
            if (empty($qq)) {
                $pattern = '/(\d+)\@qq\.com/';
                if (preg_match($pattern, strtolower($this->email), $matches)) {
                    $qq = $matches[1];
                }
            }
            if (!empty($qq)) {

                if (!is_dir(public_path('/storage/avatar/'))) {
                    mkdir(public_path('/storage/avatar/'), 0777, 1);
                }

                $avatar_path = '/storage/avatar/' . $this->id . '.jpg';
                $qq_pic      = get_qq_pic($qq);
                $qq_img_data = @file_get_contents($qq_pic);
                if ($qq_img_data) {
                    file_put_contents(public_path($avatar_path), $qq_img_data);
                    $hash = md5_file(public_path($avatar_path));
                    if ($hash != md5_file(public_path('/images/qq_default.png')) && $hash != md5_file(public_path('/images/qq_tim_default.png'))) {
                        $this->avatar = $avatar_path;
                        $this->save();
                    }
                }
            }
        }

        //最后使用默认头像地址
        if (empty($this->avatar)) {
            $this->avatar = '/images/avatar.jpg';
            $this->save();
        }

        return $this->avatar;
    }

    public function unreads($type = '', $num = null)
    {
        //TODO:: read cache
        $unreads = Cache::get('unreads_' . $this->id);
        if (!$unreads) {
            $unreadNotifications = $this->unreadNotifications;
            $unreads['comments'] = $unreadNotifications->sum(function ($item) {
                return $item->type == 'App\Notifications\ArticleCommented';
            });

            $unreads['chats'] = $this->chats->sum(function ($item) {
                return $item->pivot->unreads;
            });

            $unreads['requests'] = $unreadNotifications->sum(function ($item) {
                return $item->type == 'App\Notifications\CategoryRequested';
            });
            $unreads['likes'] = $unreadNotifications->sum(function ($item) {
                return $item->type == 'App\Notifications\ArticleLiked';
            });
            $unreads['follows'] = $unreadNotifications->sum(function ($item) {
                return $item->type == 'App\Notifications\UserFollowed';
            });
            $unreads['tips'] = $unreadNotifications->sum(function ($item) {
                return $item->type == 'App\Notifications\ArticleTiped';
            });
            $unreads['others'] = $unreadNotifications->sum(function ($item) {
                return !in_array($item->type, [
                    'App\Notifications\ArticleCommented',
                    'App\Notifications\CategoryRequested',
                    'App\Notifications\ArticleLiked',
                    'App\Notifications\UserFollowed',
                    'App\Notifications\ArticleTiped',
                ]);
            });
            //write cache
            Cache::put('unreads_' . $this->id, $unreads, 60);
        }

        if ($num) {
            $unreads[$type] = $num;
            //write cache
            Cache::put('unreads_' . $this->id, $unreads, 60);
        }

        if ($type) {
            return $unreads[$type] ? $unreads[$type] : null;
        }

        return $unreads;
    }

    public function forgetUnreads()
    {
        Cache::forget('unreads_' . $this->id);
    }

    public function link()
    {
        return '<a href="/user/' . $this->id . '">' . $this->name . '</a>';
    }

    public function balance()
    {
        $balance = 0;
        $last    = $this->transactions()->orderBy('id', 'desc')->first();
        if ($last) {
            $balance = $last->balance;
        }
        return $balance;
    }

    public function newReuqestCategories()
    {
        return $this->categories()->where('new_requests', '>', 0);
    }

    public function newArticle()
    {
        return $this->articles()->where('status','>',0)->orderBy('id', 'desc')->take(3)->get();
    }

    public function introduction()
    {
        return empty($this->introduction) ? '这个人很懒，一点介绍都没留下...' : $this->introduction;
    }

    public function fillForJs()
    {
        $this->introduction = $this->introduction();
        $this->avatar       = $this->avatar();
    }

    public function getLatestAvatar()
    {
        //如果用户刚更新过，刷新头像图片的浏览器缓存
        if ($this->updated_at->addMinutes(10) > now()) {
            return $this->checkAvatar() . '?t=' . time();
        }
        return $this->checkAvatar();
    }

    public function checkAvatar()
    {
        if (\App::environment('local')) {
            if (!starts_with($this->avatar, env('APP_URL'))) {
                return file_exists(public_path($this->avatar)) ? $this->avatar : env('APP_URL') . $this->avatar;
            }
        }
        return $this->avatar;
    }

}

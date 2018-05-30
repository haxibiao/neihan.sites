<?php

namespace App;

use App\Traits\TimeAgo;
use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use Notifiable, TimeAgo;

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
        'gender',
        'avatar',
        'introduction',
        'website',
        'is_editor',
        'is_seoer',
        'seo_meta',
        'seo_push',
        'seo_tj',
        'api_token',
        'enable_tips',
        'tip_words',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //relations
    public function questions()
    {
        return $this->hasMany(\App\Question::class);
    }
    public function answers()
    {
        return $this->hasMany(\App\Answer::class);
    }
    public function querylogs()
    {
        return $this->hasMany(\App\Querylog::class);
    }

    public function transactions()
    {
        return $this->hasMany(\App\Transaction::class);
    }

    public function favorites()
    {
        return $this->hasMany(\App\Favorite::class);
    }

    public function likes()
    {
        return $this->hasMany(\App\Like::class);
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function followings()
    {
        return $this->hasMany(\App\Follow::class);
    }

    public function followingCategories()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'categories');
    }

    public function followingCollections()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'collections');
    }

    public function followingUsers()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'users');
    }

    public function collections()
    {
        //默认给个文集...
        if (!Collection::where('user_id', $this->id)->count()) {
            $collection = Collection::firstOrNew([
                'user_id' => $this->id,
                'name'    => '日记本',
            ]);
            $collection->save();
            $this->count_collections = 1;
        }

        return $this->hasMany(\App\Collection::class);
    }

    public function actions()
    {
        return $this->hasMany(\App\Action::class);
    }

    public function chats()
    {
        return $this->belongsToMany(\App\Chat::class)->withPivot('with_users', 'unreads')->orderBy('updated_at', 'desc');
    }

    public function categories()
    {
        return $this->hasMany(\App\Category::class)->where('type', 'article');
    }

    public function articles()
    {
        return $this->hasMany(\App\Article::class)->where('status', '>', 0);
    }

    public function drafts()
    {
        return $this->hasMany(\App\Article::class)->where('status', 0);
    }

    public function removedArticles()
    {
        return $this->hasMany(\App\Article::class)->where('status', -1);
    }

    public function allArticles()
    {
        return $this->hasMany(\App\Article::class)->where('status', '>=', 0);
    }

    public function publishedArticles()
    {
        return $this->hasMany(\App\Article::class)->where('status', '>', 0);
    }

    public function adminCategories()
    {
        return $this->belongsToMany(\App\Category::class)->where('type', 'article')->wherePivot('is_admin', 1);
    }

    public function requestCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('approved', 0);
    }

    public function joinCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('approved', 1);
    }

    //computed props methods ................................................................................

    public function checkAdmin()
    {
        return $this->is_admin;
    }

    public function checkEditor()
    {
        return $this->is_editor || $this->is_admin;
    }

    public function isFollow($type, $id)
    {
        return $this->followings()->where('followed_type', get_polymorph_types($type))->where('followed_id', $id)->count() ? true : false;
    }

    public function isLiked($type, $id)
    {
        return $this->likes()->where('liked_type', get_polymorph_types($type))->where('liked_id', $id)->count() ? true : false;
    }

    public function avatar()
    {
        //修复没设置默认头像的
        if (empty($this->avatar)) {
            $this->avatar = '/images/avatar.jpg';
            $this->save();
        }

        $avatar_url = $this->avatar;
        if (\App::environment('local')) {
            if (!starts_with($this->avatar, env('APP_URL'))) {
                $avatar_url = file_exists(public_path($this->avatar)) ? $this->avatar : env('APP_URL') . $this->avatar;
            }
        }
        //如果用户刚更新过，刷新头像图片的浏览器缓存
        if ($this->updated_at && $this->updated_at->addMinutes(2) > now()) {
            $avatar_url = $avatar_url . '?t=' . time();
        }
        //APP 需要返回全Uri
        return starts_with($avatar_url, 'http') ? $avatar_url : url($avatar_url);
    }

    public function makeQQAvatar()
    {
        //尝试读取qq头像
        if (empty($this->email)) {
            $qq = $this->qq;
            if (empty($qq)) {
                $pattern = '/(\d+)\@qq\.com/';
                if (preg_match($pattern, strtolower($this->email), $matches)) {
                    $qq = $matches[1];
                }
            }
            if (!empty($qq)) {
                $avatar_path = '/storage/avatar/' . $this->id . '.qq.jpg';
                $qq_pic      = get_qq_pic($qq);
                $qq_img_data = @file_get_contents($qq_pic);
                if ($qq_img_data) {
                    file_put_contents(public_path($avatar_path), $qq_img_data);
                    $hash = md5_file(public_path($avatar_path));
                    if ($hash != md5_file(public_path('/images/qq_default.png')) && $hash != md5_file(public_path('/images/qq_tim_default.png'))) {
                        $this->avatar = $avatar_path;
                    }
                }
            }
        }
        $this->save();

        return $this->avatar;
    }

    public function unreads($type = null, $num = null)
    {
        $unreads = Cache::get('unreads_' . $this->id);
        if (!$unreads) {
            $unreadNotifications = $this->unreadNotifications;
            $unreads['comments'] = $unreadNotifications->sum(function ($item) {
                return $item->type == 'App\Notifications\ArticleCommented';
            });

            $unreads['chats'] = $this->chats->sum(function ($item) {
                return $item->pivot->unreads;
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
                    'App\Notifications\ArticleLiked',
                    'App\Notifications\UserFollowed',
                    'App\Notifications\ArticleTiped',
                ]);
            });

            $unreads['requests'] = $this->adminCategories()->sum('new_requests');
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

    public function newReuqestCategories()
    {
        return $this->adminCategories()->where('new_requests', '>', 0);
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

    public function introduction()
    {
        return empty($this->introduction) ? '这个人很懒，一点介绍都没留下...' : $this->introduction;
    }

    public function ta()
    {
        return $this->isSelf() ? '我' : '他';
    }

    public function isSelf()
    {
        return Auth::check() && Auth::id() == $this->id;
    }

    public function fillForJs()
    {
        $this->introduction = $this->introduction();
        $this->avatar       = $this->avatar();
    }

    public function blockedUsers()
    {
        $json = json_decode($this->json, true);
        if (!$json) {
            $json = (object) [];
        }

        $blocked = [];
        if (isset($json['blocked'])) {
            $blocked = $json['blocked'];
        }
        return $blocked;
    }

    public function blockUser($user_id)
    {
        $user = User::findOrFail($user_id);
        $json = json_decode($this->json, true);
        if (!$json) {
            $json = (object) [];
        }

        $blocked = [];
        if (isset($json['blocked']) && is_array($json['blocked'])) {
            $blocked = $json['blocked'];
        }

        $blocked = new Illuminate\Support\Collection($blocked);

        if ($blocked->contains('id', $user_id)) {
            //unbloock
            $blocked = $blocked->filter(function ($value, $key) use ($user_id) {
                return $value['id'] != $user_id;
            });
        } else {
            $blocked[] = [
                'id'     => $user->id,
                'name'   => $user->name,
                'avatar' => $user->avatar(),
            ];
        }

        $json['blocked'] = $blocked;
        $this->json      = json_encode($json, JSON_UNESCAPED_UNICODE);
        $this->save();
    }
}

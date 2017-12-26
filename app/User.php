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
        'introduction',
        'is_editor',
        'is_seoer',
        'seo_meta',
        'seo_push',
        'seo_tj',
        'api_token',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function followings()
    {
        return $this->hasMany(\App\Follow::class);
    }

    //取出该用户关注的专题
    public function followingCategories()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'categories');
    }

    public function isSelf()
    {
        return Auth::check() && Auth::id() == $this->id;
    }

    public function transactions()
    {
        return $this->hasMany(\App\Transaction::class);
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    public function messages()
    {
        return $this->hasMany(\App\Message::class);
    }

    public function categories()
    {
        return $this->hasMany(\App\Category::class);
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

    public function likes()
    {
        return $this->morphMany(\App\Like::class, 'liked');
    }

    public function favorites()
    {
        return $this->morphMany(\App\Favorite::class, 'faved');
    }

    public function collection()
    {
        return $this->hasMany(\App\Collection::class);
    }

    public function isFollow($type, $id)
    {
        return $this->followings()->where('followed_type', get_polymorph_types($type))->where('followed_id', $id)->count() ? true : false;
    }

    public function chats()
    {
        return $this->belongsToMany(\App\Chat::class)->withPivot('with_users', 'unreads');
    }

    public function adminCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('is_admin', 1);
    }

    public function requestCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('approved', 0);
    }

    public function joinCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('approved', 1);
    }

    public function introduction()
    {
        return !empty($this->introduction) ? $this->introduction : '这个人比较神秘,什么都没有留下...';
    }

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

}

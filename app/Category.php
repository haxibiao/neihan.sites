<?php

namespace App;

use App\Model;
use Auth;

class Category extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'description',
        'user_id',
        'parent_id',
        'type',
        'order',
        'status', 
        'request_status',
        'is_official',
        'is_for_app',
        'logo_app',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function admins()
    {
        return $this->belongsToMany('App\User')->wherePivot('is_admin', 1)->withTimestamps();
    }

    public function authors()
    {
        return $this->belongsToMany('App\User')
            ->withTimestamps()->withPivot('count_approved')
            ->wherePivot('count_approved', '>', 0)
            ->orderBy('pivot_count_approved', 'desc');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function videoArticles()
    {
        return $this->belongsToMany('App\Article')
            ->where('articles.type', 'video');
    }

    public function hasManyVideoArticles()
    {
        return $this->hasMany('App\Article')->where('articles.type', 'video');
    }

    public function questions()
    {
        return $this->belongsToMany('App\Question');
    }

    public function articles()
    {
        return $this->belongsToMany('App\Article')
            ->withPivot('submit')
            ->withTimestamps()
            ->orderBy('pivot_updated_at', 'desc');
    }

    public function videoPosts()
    {
        return $this->articles()->where('type', 'video');
    }

    public function newRequestArticles()
    {
        return $this->belongsToMany('App\Article')
            ->wherePivot('submit', '待审核')
            ->withPivot('submit', 'updated_at')
            ->withTimestamps()
            ->orderBy('id', 'desc');
    }

    public function requestedInMonthArticles()
    {
        return $this->belongsToMany('App\Article')
            ->wherePivot('created_at', '>', \Carbon\Carbon::now()->addDays(-90))
            ->withPivot('submit', 'created_at')
            ->withTimestamps()
            ->orderBy('pivot_created_at', 'desc');
    }

    public function publishedArticles()
    {
        return $this->belongsToMany('App\Article')
            ->where('articles.status', '>', 0) //TODO:: double check fix existing status = 1 articles pivot submit  ...
            ->whereType('article')
            ->wherePivot('submit', '已收录')
            ->withPivot('submit')
            ->withTimestamps();
    }

    public function parent()
    {
        return $this->belongsTo(\App\Category::class, 'parent_id');
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    //methods .............

    public function fillForJs()
    {
        $this->logo        = $this->logo();
        $this->description = $this->description();
    }

    public function logo() 
    {
        $path = empty($this->logo) ? '/images/category.logo.jpg' : $this->logo;
        $url  = file_exists(public_path($path)) ? $path : starts_with($path, 'http') ? $path : env('APP_URL') . $path;
        //如果用户刚更新过，刷新头像图片的浏览器缓存
        if ($this->updated_at && $this->updated_at->addMinutes(2) > now()) {
            $url = $url . '?t=' . time(); 
        }
        //APP 需要返回全Uri
        return starts_with($url, 'http') ? $url : url($url);
    }

    public function logo_app()
    {
        $path = empty($this->logo_app) ? '/images/category.logo.jpg' : $this->logo_app;
        $url  = file_exists(public_path($path)) ? $path : env('APP_URL') . $path;
        //如果用户刚更新过，刷新头像图片的浏览器缓存
        if ($this->updated_at && $this->updated_at->addMinutes(2) > now()) {
            $url = $url . '?t=' . time();
        }
        //APP 需要返回全Uri
        return starts_with($url, 'http') ? $url : url($url);
    }

    public function smallLogo()
    {
        $path = empty($this->logo) ? '/images/category.logo.small.jpg' : str_replace('.logo.jpg', '.logo.small.jpg', $this->logo);
        $url  = file_exists(public_path($path)) ? $path : env('APP_URL') . $path;

        //APP 需要返回全Uri
        return starts_with($url, 'http') ? $url : url($url);
    }

    public function topAdmins()
    {
        $topAdmins = $this->admins()->orderBy('id', 'desc')->take(10)->get();
        foreach ($topAdmins as $admin) {
            $admin->isCreator = $admin->id == $this->user_id;
        }
        return $topAdmins;
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

    public function topAuthors()
    {
        $topAuthors = $this->authors()->orderBy('id', 'desc')->take(8)->get();
        return $topAuthors;
    }

    public function topFollowers()
    {
        $topFollows   = $this->follows()->orderBy('id', 'desc')->take(8)->get();
        $topFollowers = [];
        foreach ($topFollows as $follow) {
            $topFollowers[] = $follow->user;
        }
        return $topFollowers;
    }

    public function description()
    {
        return empty($this->description) ? '这专题管理很懒，一点介绍都没留下...' : $this->description;
    }

    public function link()
    {
        return '<a href="/' . $this->name_en . '">' . $this->name . '</a>';
    }

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

    public function saveLogo($request)
    {
        if ($request->logo) {
            $storage_category = '/storage/category/';
            if (!is_dir(public_path($storage_category))) {
                mkdir(public_path($storage_category), 0777, 1);
            }
            $id         = $this->id ? $this->id : "c" . (\App\Category::max('id') + 1) . "_" . time();
            $this->logo = $storage_category . $id . '.logo.jpg';
            if( file_exists(public_path($this->logo)) ){
                unlink(public_path($this->logo));
            }
            $img        = \ImageMaker::make($request->logo->path());
            $img->fit(180);
            $img->save(public_path($this->logo));

            $img->fit(32);
            $small_logo = $storage_category . $this->id . '.logo.small.jpg';
            if( file_exists(public_path($small_logo)) ){
                unlink(public_path($small_logo));
            }
            $img->save(public_path($small_logo));
        }

        if ($request->logo_app) {
            $storage_category = '/storage/category/';
            if (!is_dir(public_path($storage_category))) {
                mkdir(public_path($storage_category), 0777, 1);
            }
            $this->logo_app = $storage_category . $this->id . '.logo.app.jpg';
            $img            = \ImageMaker::make($request->logo_app->path());
            $img->fit(180);
            if( file_exists(public_path($this->logo_app)) ){
                unlink(public_path($this->logo_app));
            }
            $img->save(public_path($this->logo_app));

            $img->fit(32);
            $small_logo = $storage_category . $this->id . '.logo.small.app.jpg';
            if( file_exists(public_path($this->small_logo)) ){
                unlink(public_path($this->small_logo));
            }
            $img->save(public_path($small_logo));
        }
    }
    /**
     * @Desc     记录用户浏览记录
     * @DateTime 2018-06-28
     * @return   [type]     [description]
     */
    public function recordBrowserHistory()
    {
        //记录浏览历史
        if (checkUser()) {
            $user = getUser();
            //如果重复浏览只更新纪录的时间戳
            \App\Visit::updateOrCreate([
                'user_id'      => $user->id,
                'visited_type' => 'categories',
                'visited_id'   => $this->id,
            ]);
        }
    }
    public function isSelf()
    {
        return Auth::check() 
                && 
            Auth::user()
                ->categories()
                ->where('categories.id',$this->id)
                ->exists() ;
    }

    public function publishedWorks()
    {
        return $this->belongsToMany('App\Article')
            ->where('articles.status', '>', 0) //TODO:: double check fix existing status = 1 articles pivot submit  ...
            ->wherePivot('submit', '已收录')
            ->withPivot('submit')
            ->withTimestamps();
    }
}

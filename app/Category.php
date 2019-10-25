<?php

namespace App;

use App\Helpers\QcloudUtils;
use App\Model;
use App\Traits\CategoryAttrs;
use App\Traits\CategoryResolvers;
use Auth;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use CategoryResolvers;
    use CategoryAttrs;

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

    public function containedVideoPosts()
    {
        return $this->hasMany('App\Article')->where('articles.type', 'video');
    }

    public function issues()
    {
        return $this->belongsToMany('App\Issue');
    }

    public function articles()
    {
        return $this->belongsToMany('App\Article')
            ->withPivot('submit')
            ->withTimestamps()
            ->orderBy('pivot_updated_at', 'desc')
            ->exclude(['body', 'json']);
    }

    public function videoPosts()
    {
        return $this->articles()->where('type', 'video');
    }

    public function newRequestArticles()
    {
        return $this->articles()
            ->wherePivot('submit', '待审核')
            ->withPivot('updated_at');
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
        return $this->articles()
            ->where('articles.status', '>', 0)
            ->wherePivot('submit', '已收录');
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
        $this->logo = $this->logoUrl;
        $this->description = $this->description();
    }

    public function logo_app()
    {
        return cdnurl($this->logo_app);
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
        $topFollows = $this->follows()->orderBy('id', 'desc')->take(8)->get();
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
        return '<a href="/category/' . $this->id . '">' . $this->name . '</a>';
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

    //TODO:待重构repo
    public function saveLogo($request)
    {
        $name = $this->id . '_' . time();
        if ($request->logo) {
            $file = $request->logo;
            $extension = $file->getClientOriginalExtension();
            $file_name_formatter = $name . '.%s.' . $extension;
            //save logo
            $file_name_big = sprintf($file_name_formatter, 'logo');
            $tmp_big = '/tmp/' . $file_name_big;
            $img = \ImageMaker::make($file->path());
            $img->fit(180);
            $img->save($tmp_big);
            $cos_file_info = QcloudUtils::uploadFile($tmp_big, $file_name_big, 'category');
            //上传到COS失败
            if (empty($cos_file_info) || $cos_file_info['code'] != 0) {
                return;
            }
            //save small logo
            $img->fit(32);
            $file_name_small = sprintf($file_name_formatter, 'logo.small');
            $tmp_small = '/tmp/' . $file_name_small;
            $img->save($tmp_small);
            QcloudUtils::uploadFile($tmp_small, $file_name_small, 'category');
            $this->logo = $cos_file_info['data']['custom_url'];
        }

        if ($request->logo_app) {
            $file = $request->logo_app;
            $extension = $file->getClientOriginalExtension();
            $file_name_formatter = $name . '.%s.' . $extension;
            //save logo_app
            $file_name_big = sprintf($file_name_formatter, 'logo.app');
            $tmp_big = '/tmp/' . $file_name_big;
            $img = \ImageMaker::make($file->path());
            $img->fit(180);
            $img->save($tmp_big);
            $cos_file_info = QcloudUtils::uploadFile($tmp_big, $file_name_big, 'category');
            //上传到COS失败
            if (empty($cos_file_info) || $cos_file_info['code'] != 0) {
                return;
            }
            //save small logo_app
            $img->fit(32);
            $file_name_small = sprintf($file_name_formatter, 'logo.small.app');
            $tmp_small = '/tmp/' . $file_name_small;
            $img->save($tmp_small);
            QcloudUtils::uploadFile($tmp_small, $file_name_small, 'test');
            $this->logo_app = $cos_file_info['data']['custom_url'];
        }
    }

    public function recordBrowserHistory()
    {
        //记录浏览历史
        if (checkUser()) {
            $user = getUser();
            //如果重复浏览只更新纪录的时间戳
            $visited = \App\Visit::firstOrNew([
                'user_id' => $user->id,
                'visited_type' => 'categories',
                'visited_id' => $this->id,
            ]);
            $visited->updated_at = now();
            $visited->save();
        }
    }

    //TODO: attrs
    public function isSelf()
    {
        return Auth::check()
        &&
        Auth::user()
            ->categories()
            ->where('categories.id', $this->id)
            ->exists();
    }

    public function publishedWorks()
    {
        return $this->belongsToMany('App\Article')
            ->where('articles.status', '>', 0) //TODO:: double check fix existing status = 1 articles pivot submit  ...
            ->wherePivot('submit', '已收录')
            ->withPivot('submit')
            ->withTimestamps();
    }

    public function subCategory()
    {
        return $this->hasMany('App\Category', 'parent_id', 'id');
    }

    public function hasManyArticles()
    {
        return $this->hasMany('App\Article', 'category_id', 'id');
    }

    public static function getTopCategory($number = 5)
    {
        $data = [];
        $ten_top_category = Category::select(DB::raw('count(*) as categoryCount,category_id'))
            ->from('articles')
            ->where('type', 'video')
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderBy('categoryCount', 'desc')
            ->take($number)->get()->toArray();

        foreach ($ten_top_category as $top_category) {
            $cate = Category::find($top_category["category_id"]);
            $data['name'][] = $cate ? $cate->name : '空';
            $data['data'][] = $top_category["categoryCount"];
        }
        return $data;
    }

    public static function getTopLikeCategory($number = 5)
    {
        $data = [];

        $ten_top_category = Category::select(DB::raw('sum(count_likes) as categoryCount,category_id'))
            ->from('articles')
            ->where('type', 'video')
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderBy('categoryCount', 'desc')
            ->take($number)->get()->toArray();

        foreach ($ten_top_category as $top_category) {
            $cate = Category::find($top_category["category_id"]);
            $data['options'][] = $cate ? $cate->name : '空';
            $data['value'][] = $top_category["categoryCount"];
        }
        return $data;
    }
}

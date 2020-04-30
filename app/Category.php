<?php

namespace App;

use App\Follow;
use App\Model;
use App\Traits\CategoryAttrs;
use App\Traits\CategoryRepo;
use App\Traits\CategoryResolvers;
use App\User;
use Carbon\Carbon;

class Category extends Model
{
    use CategoryResolvers;
    use CategoryAttrs;
    use CategoryRepo;

    const LOGO_PATH = '/images/category.logo.jpg';

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'logo',
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

    public function publishedWorks()
    {
        return $this->belongsToMany('App\Article')
            ->where('articles.status', '>', 0)
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

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}

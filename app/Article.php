<?php

namespace App;

use App\Category;
use App\Comment;
use App\Favorite;
use App\Like;
use App\Model;
use App\Tip;
use App\Traits\ArticleAttrs;
use App\Traits\ArticleRepo;
use App\Traits\ArticleResolvers;
use App\Video;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use ArticleRepo;
    use ArticleResolvers;
    use ArticleAttrs;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'keywords',
        'description',
        'author',
        'author_id',
        'user_id',
        'category_id',
        'collection_id',
        'body',
        'count_words',
        'cover',
        'is_top',
        'status',
        'source_url',
        'hits',
        'count_likes',
        'count_comments',
        'type',
        'video_id',
        'slug',
        'submit',
        'cover_path',
    ];

    protected static function boot()
    {
        parent::boot();

        // 这里添加全局搜索scope的话 nova 创建动态会报错，先关闭
        //static::addGlobalScope(new ArticleSubmitScope);
    }

    //提交状态
    const REFUSED_SUBMIT   = -1; //已拒绝
    const REVIEW_SUBMIT    = 0; //待审核
    const SUBMITTED_SUBMIT = 1; //已收录

    protected $touches = ['category', 'collection', 'categories'];

    protected $dates = ['edited_at', 'delay_time', 'commented'];

    public function issue()
    {
        return $this->belongsTo('App\Issue');
    }

    //relations
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function video()
    {
        return $this->belongsTo('App\Video');
    }

    //主专题
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    //主文集
    public function collection()
    {
        return $this->belongsTo('App\Collection');
    }

    //所有分类关系，包含未审核通过的
    public function allCategories()
    {
        return $this->belongsToMany(\App\Category::class)
            ->withPivot(['id', 'submit'])
            ->withTimestamps();
    }

    public function favorites()
    {
        return $this->morphMany(\App\Favorite::class, 'faved');
    }

    public function hasCategories()
    {
        return $this->belongsToMany(\App\Category::class);
    }

    //有效的分类关系
    public function categories()
    {
        return $this->belongsToMany(\App\Category::class)
            ->wherePivot('submit', '已收录')
            ->withPivot(['id', 'submit'])
            ->withTimestamps();
    }

    public function fixcategories()
    {
        return $this->belongsToMany(\App\Category::class)
            ->wherePivot('submit', null)
            ->withPivot(['id', 'submit'])
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(\App\Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphMany(\App\Like::class, 'liked');
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function images()
    {
        return $this->belongsToMany('App\Image')->withTimestamps();
    }

    public function tips()
    {
        return $this->morphMany(\App\Tip::class, 'tipable');
    }

    public function relatedVideoPostsQuery()
    {
        return Article::with(['video' => function ($query) {
            //过滤软删除的video
            $query->whereStatus(1);
        }])->where('type', 'video')
            ->whereIn('category_id', $this->categories->pluck('id'));
    }

    public function save(array $options = array())
    {
        $this->description = $this->summary;
        parent::save($options);
    }

    public static function getSubmitStatus()
    {
        return [
            self::SUBMITTED_SUBMIT => '已收录',
            self::REVIEW_SUBMIT    => '待审核',
            self::REFUSED_SUBMIT   => '已拒绝',
        ];
    }
}

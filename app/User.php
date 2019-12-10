<?php

namespace App;

use App\Feedback;
use App\Traits\UserAttrs;
use App\Traits\UserRepo;
use App\Traits\UserResolvers;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use UserAttrs;
    use UserRepo;
    use UserResolvers;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid',
        'phone',
        'account',
        'email',
        'avatar',
        'password',
        'api_token',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * 性别
     */
    const MALE_GENDER   = 0;
    const FEMALE_GENDER = 1;

    // 正常状态
    const STATUS_ONLINE = 0;

    // 冻结状态
    const STATUS_OFFLINE = 1;

    // 注销状态
    const STATUS_DESTORY = 1;

    // 默认头像
    const AVATAR_DEFAULT = 'storage/avatar/avatar-1.jpg';

    const DEFAULT_NAME = '匿名用户';

    //

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function exchanges()
    {
        return $this->hasMany(\App\Exchange::class);
    }

    public function contributes()
    {
        return $this->hasMany(\App\Contribute::class);
    }

    //TODO 临时过渡
    public function getCashAttribute()
    {
        $tansaction = $this->transactions()
            ->latest()->first();
        if (!$tansaction) {
            return 0;
        }
        return $tansaction->balance;
    }

    public function feedbacks()
    {
        return $this->hasMany(\App\Feedback::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(\App\Task::class, 'user_task')
            ->withPivot(['status', 'content', 'id'])
            ->withTimestamps();
    }

    //问答
    public function issues()
    {
        return $this->hasMany(\App\Issue::class);
    }
    public function resolutions()
    {
        return $this->hasMany(\App\Resolution::class);
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

    public function favoritedArticles()
    {
        return $this->hasMany(\App\Favorite::class)->where('faved_type', 'articles');
    }

    public function likes()
    {
        return $this->hasMany(\App\Like::class);
    }

    public function likedArticles()
    {
        return $this->hasMany(\App\Like::class)->where('liked_type', 'articles');
    }
    
    public function userBlock(){
        return $this->hasMany(\App\UserBlock::class);
    }
    
    #trick!! 这里其实是关注这个用户的粉丝
    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    #trick!! 这里才是用户的一对多的关注行为记录
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

    public function visitedArticles()
    {
        return $this->visits()->where('visited_type', 'articles');
    }

    public function VisitedVideos(): HasMany
    {
        return $this->visits()->where('visited_type', 'videos');
    }

    public function golds(): hasMany
    {
        return $this->hasMany(Gold::class);
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
        return $this->belongsToMany(\App\Chat::class, 'chat_user')
            ->withPivot('unreads')
            ->orderBy('updated_at', 'desc');
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Category::class);
    }

    public function drafts()
    {
        return $this->hasMany(\App\Article::class)->where('status', 0);
    }

    public function articles()
    {
        return $this->hasMany(\App\Article::class)
            ->where('status', '>', 0)
            ->exclude(['body', 'json']);
    }

    public function removedArticles()
    {
        return $this->hasMany(\App\Article::class)->where('status', -1);
    }

    public function allArticles()
    {
        return $this->hasMany(\App\Article::class)
            ->exclude(['body', 'json']);
    }

    public function allVideoPosts()
    {
        return $this->allArticles()->where('type', 'video');
    }

    public function publishedArticles()
    {
        return $this->allArticles()->where('status', '>', 0);
    }

    public function videoPosts()
    {
        return $this->publishedArticles()->where('type', 'video');
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

    public function hasManyCategories()
    {
        return $this->hasMany(\App\Category::class, 'user_id', 'id')->where('type', 'article');
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(\App\Wallet::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function newReuqestCategories()
    {
        return $this->adminCategories()->orderBy('new_requests', 'desc')->orderBy('updated_at', 'desc');
    }

    public function videoArticles()
    {
        return $this->hasMany('App\Article')
            ->where('articles.type', 'video');
    }

    public function issueInvites()
    {
        return $this->hasMany('App\IssueInvite', 'user_id', 'id');
    }

    public static function getGenders()
    {
        return [
            self::MALE_GENDER   => '男',
            self::FEMALE_GENDER => '女',
        ];
    }
}

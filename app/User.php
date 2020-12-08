<?php

namespace App;

use App\Traits\CanCustomizeTask;
use App\Traits\CanLike;
use App\Traits\CanNotLike;
use App\Traits\CanTag;
use App\Traits\UserAttrs;
use App\Traits\UserRepo;
use App\Traits\UserResolvers;
use Haxibiao\Base\Traits\AvatarHelper;
use Haxibiao\Content\Traits\HasContent;
use Haxibiao\Media\Traits\WithMedia;
use Haxibiao\Sns\Traits\CanFollow;
use Haxibiao\Task\Traits\PlayWithTasks;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract
{
    use \Illuminate\Auth\Authenticatable, Authorizable;

    use Notifiable;
    use UserAttrs;
    use UserRepo;
    use UserResolvers;
    use WithMedia;
    use HasContent;
    use CanTag;
    use CanLike;
    use AvatarHelper;
    use CanNotLike;
    use CanFollow;
    use PlayWithTasks;
    use CanCustomizeTask;

    use \Haxibiao\Helpers\Traits\CanCacheAttributes;

    protected $with = ['hasOneProfile'];
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
        'role_id',
        'ticket',
    ];

    /**
     * 性别
     */
    const MALE_GENDER   = 0;
    const FEMALE_GENDER = 1;

    // 正常状态
    const STATUS_ONLINE = 0;

    //以前奇怪的冻结账户，怎么用status=1啊...
    //FIXME: 这个后面要修复为-1, 注销修复为-2, 负数的status都是异常的

    // 封禁状态
    const STATUS_OFFLINE = 1;

    //暂时冻结的账户
    const STATUS_FREEZE = -1;

    // 注销状态
    const STATUS_DESTORY = -2; //这个注销状态值太诡异

    // 默认头像
    const AVATAR_DEFAULT = 'storage/avatar/avatar-1.jpg';

    const DEFAULT_NAME = '匿名用户';
    //

    /**
     * 编辑身份
     */
    const USER_STATUS   = 0;
    const EDITOR_STATUS = 1;
    const ADMIN_STATUS  = 2;
    const VEST_STATUS   = 3;

    //用户激励视频奖励
    const VIDEO_REWARD_GOLD       = 10;
    const VIDEO_REWARD_TICKET     = 10;
    const VIDEO_REWARD_CONTRIBUTE = 6;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 用户状态 -2:禁用(禁止提现) -1:禁言 0:正常启用
     */
    const DISABLE_STATUS = -2;
    const MUTE_STATUS    = -1;
    const ENABLE_STATUS  = 0;

    public static function boot()
    {
        parent::boot();
        self::saving(function ($user) {
            if ($user->isDirty(['name'])) {
                $user->name = app('SensitiveUtils')->replace($user->name, '*');
            }
            if (empty($user->api_token)) {
                $user->api_token = str_random(60);
            }
        });
    }

    public function hasOneProfile()
    {
        return $this->hasOne(Profile::class);
    }

    public function withdraws(): HasManyThrough
    {
        return $this->hasManyThrough(\App\Withdraw::class, \App\Wallet::class);
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function retentions()
    {
        return $this->hasMany(\App\UserRetention::class);
    }

    public function exchanges()
    {
        return $this->hasMany(\App\Exchange::class);
    }

    public function contributes()
    {
        return $this->hasMany(\App\Contribute::class);
    }


    public function feedbacks()
    {
        return $this->hasMany(\App\Feedback::class);
    }

    //问答
    public function issues()
    {
        return $this->hasMany(\App\Issue::class);
    }
    public function resolutions()
    {
        return $this->hasMany(\App\Solution::class);
    }
    public function solutions()
    {
        return $this->hasMany(\App\Solution::class);
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

    public function userBlock()
    {
        return $this->hasMany(\App\UserBlock::class);
    }

    public function followingCategories()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'categories');
    }

    public function followingCollections()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'collections');
    }
    
    public function movieHistory(): HasMany
    {
        return $this->hasMany(MovieHistory::class);
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

    public function wallets(): HasMany
    {
        return $this->hasMany(\App\Wallet::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
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

    public function oauth(): HasMany
    {
        return $this->hasMany(OAuth::class);
    }

    public function saveDownloadImage($file)
    {
        if ($file) {
            $avatar  = 'avatar/avatar' . $this->id . '_' . time() . '.png';
            $cosDisk = \Storage::cloud();
            $cosDisk->put($avatar, \file_get_contents($file->path()));

            return $avatar;
        }
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar;
    }
}

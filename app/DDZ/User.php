<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use UserAttrs;

    protected $connection = 'dongdezhuan';

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
        'level_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // 性别
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

    const DEFAULT_NAME = '槐序十七';

    public function save(array $options = array())
    {
        parent::save($options);

        //维护懂得赚的数据完整性
        Profile::firstOrCreate([
            'user_id' => $this->id,
        ], [
            'user_id'      => $this->id,
            'introduction' => sprintf('我是从%s来的小白,望多多指教!~', config('app.name_cn')),
        ]);
    }

    public static function getGenders(): array
    {
        return [
            self::MALE_GENDER   => '男',
            self::FEMALE_GENDER => '女',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(\App\DDZ\Product::class, 'user_products')
            ->whereNull('user_products.deleted_at')
            ->withPivot(['scope', 'expired_at']);
    }

    public function appTasks(): HasMany
    {
        return $this->hasMany(\App\DDZ\AppTask::class);
    }

    public function golds(): hasMany
    {
        return $this->hasMany(\App\DDZ\Gold::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(\App\DDZ\Wallet::class);
    }

    public function apps(): BelongsToMany
    {
        return $this->belongsToMany(\App\DDZ\App::class, 'user_apps')
            ->withPivot(['user_id', 'app_id', 'data'])
            ->withTimestamps();
    }

}

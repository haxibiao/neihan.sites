<?php

namespace App\Dongdezhuan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Psy\Util\Str;

class User extends Model
{

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
        'level_id'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    public function golds(): hasMany
    {
        return $this->hasMany(\App\Dongdezhuan\Gold::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(\App\Dongdezhuan\Wallet::class);
    }

    public function apps(): BelongsToMany
    {
        return $this->belongsToMany(\App\Dongdezhuan\App::class, 'user_apps')
            ->withPivot(['user_id', 'app_id', 'data'])
            ->withTimestamps();
    }

    public static function getGenders():array
    {
        return [
            self::MALE_GENDER   => '男',
            self::FEMALE_GENDER => '女',
        ];
    }

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

    const DEFAULT_NAME = '槐序十七';

    //rmb钱包，默认钱包
    public function getWalletAttribute()
    {
        if ($wallet = $this->wallets()->whereType(0)->first()) {
            return $wallet;
        }

        return Wallet::rmbWalletOf($this);
    }

    public function getAvatarUrlAttribute(){
        if (\Illuminate\Support\Str::contains($this->avatar,'http')){
            return $this->avatar;
        }
//        懂得赚 filesystem cdn url
        return 'http://cos-dongdezhuan.dianmoge.com/'.$this->avatar;
    }
}

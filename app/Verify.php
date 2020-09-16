<?php

namespace App;

use App\Traits\VerifyRepo;
use App\Traits\VerifyResolvers;
use Illuminate\Database\Eloquent\Model;

class Verify extends Model
{

    use VerifyRepo;
    use VerifyResolvers;

    // 重置密码
    const RESET_PASSWORD = 'RESET_PASSWORD';

    // 改变重要信息（支付宝..）
    const USER_INFO_CHANGE = 'USER_INFO_CHANGE';

    const USER_REGISTER    = 'USER_REGISTER';
    const USER_LOGIN       = 'USER_LOGIN';

    const WECHAT_BIND      = 'WECHAT_BIND';
    const EXCHANGE_REMIND  = 'EXCHANGE_REMIND';

    const CODE_VALID_TIME = 60;

    protected $fillable = [
        'account',
        'code',
        'channel',
        'action',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getActionEnumTypes()
    {
        return [
            self::RESET_PASSWORD   => [
                'value'       => self::RESET_PASSWORD,
                'description' => '修改密码',
            ],
            self::USER_INFO_CHANGE => [
                'value'       => self::USER_INFO_CHANGE,
                'description' => '修改信息',
            ],
            self::USER_REGISTER   => [
                'value'       => self::USER_REGISTER,
                'description' => '用户注册',
            ],
            self::USER_LOGIN => [
                'value'       => self::USER_LOGIN,
                'description' => '用户登录',
            ],
        ];
    }

    public static function getVerificationActions()
    {
        return [
            self::RESET_PASSWORD   => [
                'sms'  => self::RESET_PASSWORD,
                'mail' => '修改密码',
            ],
            self::USER_INFO_CHANGE => [
                'sms'  => self::USER_INFO_CHANGE,
                'mail' => '修改信息',
            ],
            self::USER_REGISTER => [
                'sms'  => self::USER_REGISTER,
                'mail' => '用户注册',
            ],
            self::USER_LOGIN => [
                'sms'  => self::USER_LOGIN,
                'mail' => '用户登录',
            ],
        ];
    }
}

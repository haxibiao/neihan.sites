<?php

namespace App;

use App\Traits\VerifyResolvers;
use Illuminate\Database\Eloquent\Model;

class Verify extends Model
{

    use VerifyResolvers;

    // 重置密码
    const RESET_PASSWORD = 'RESET_PASSWORD';

    // 改变重要信息（支付宝..）
    const USER_INFO_CHANGE = 'USER_INFO_CHANGE';

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
                'description' => '修改密码',
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
                'mail' => '修改密码',
            ],
        ];
    }
}

<?php

namespace App\Traits;

use App\OAuth;
use App\User;
use Illuminate\Support\Arr;

trait OAuthRepo
{
    public static function getUserOauth(User $user, $oAuthType)
    {
        return OAuth::where(['oauth_type' => $oAuthType, 'user_id' => $user->id])->first();
    }

    public static function findWechatUser($unionId)
    {
        $oAuth = OAuth::where('oauth_type', 'wechat')->where('oauth_id', $unionId)->first();

        if (!is_null($oAuth)) {
            return $oAuth->user;
        }
    }

    /**
     * @return array
     */
    public static function getTypeEnums()
    {
        return [
            'TIKTOK'      => [
                'value'       => 'tiktok',
                'description' => '抖音',
            ],
            'DONGDEZHUAN' => [
                'value'       => 'dongdezhuan',
                'description' => '懂得赚',
            ],
        ];
    }

    /**
     * @param $type
     * @param string $language
     * @return mixed
     */
    public static function typeTranslator($type, $language = 'zh')
    {
        $types = [
            'wechat'      => '微信',
            'alipay'      => '支付宝',
            'tiktok'      => '抖音',
            'dongdezhuan' => '懂得赚',
        ];
        return Arr::get($types, $type, '授权');
    }

    public static function createRelation($user_id, $oauth_type, $oauth_id, $data = null)
    {
        return OAuth::firstOrCreate([
            'user_id'    => $user_id,
            'oauth_type' => $oauth_type,
            'oauth_id'   => $oauth_id,
            'data'       => $data,
        ]);
    }

    public static function isExists($type, $id): bool
    {
        return self::where([
            'oauth_type' => $type,
            'oauth_id'   => $id,
        ])->exists();
    }
}

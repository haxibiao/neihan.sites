<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class OAuth extends Model
{
    protected $fillable = [
        'user_id',
        'oauth_id',
        'oauth_type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $unionId
     * @return mixed
     */
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
            'TIKTOK' => [
                'value'       => 'tiktok',
                'description' => '抖音',
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
            'wechat' => '微信',
            'alipay' => '支付宝',
            'tiktok' => '抖音',
        ];
        return Arr::get($types, $type, '授权');
    }
}

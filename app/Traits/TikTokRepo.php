<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use Haxibiao\Breeze\OAuth;
use Haxibiao\Helpers\utils\TikTokUtils;
use Illuminate\Support\Arr;

trait TikTokRepo
{
    public function bindTikTok($code, $isSignUp = false)
    {
        throw_if(empty($code), GQLException::class, '绑定失败,参数错误!');

        $tikTokUtils  = new TikTokUtils();
        $accessTokens = $tikTokUtils->accessToken($code);
        $openId       = Arr::get($accessTokens, 'open_id');
        throw_if(empty($openId), GQLException::class, '授权失败,请稍后再试!');

        $oauth = OAuth::firstOrNew(['oauth_type' => 'tiktok', 'oauth_id' => $openId]);

        throw_if(isset($oauth->id), GQLException::class, '该抖音已被绑定,请尝试其他账户!');

        $userInfo = $tikTokUtils->userInfo($accessTokens['access_token'], $openId);

        $oauth->user_id = $this->id;
        $oauth->data    = $userInfo;
        $oauth->save();

        if ($isSignUp == true) {
            $this->avatar = Arr::get($accessTokens, 'avatar');
            $this->name   = Arr::get($accessTokens, 'nickname');
            $this->save();
        }

        return $oauth;
        //同步昵称、头像、性别...
    }
}

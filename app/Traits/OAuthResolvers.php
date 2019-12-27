<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\Helpers\AlipayUtils;
use App\Helpers\WechatAppUtils;
use App\OAuth;
use App\User;
use App\Wallet;
use App\Withdraw;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait OAuthResolvers
{
    public function bindOAuth($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();
        $code = Arr::get($args, 'code');
        $type = Arr::get($args, 'oauth_type');
        throw_if(empty($code) || empty($type), GQLException::class, '绑定失败,参数错误!');

        return $this->bind($user, $code, $type);
    }

    public function bind(User $user, $code, $type)
    {
        // throw_if(OAuth::getUserOauth($user, $type), GQLException::class, '您已绑定成功,请直接登录!');
        throw_if(!method_exists($this, $type), GQLException::class, '绑定失败,该授权方式不存在!');
        $oauth = $this->$type($user, $code);

        return $oauth;
    }

    public function wechat(User $user, $code)
    {
        $utils = new WechatAppUtils;

        //获取微信token
        $accessTokens = $utils->accessToken($code);
        throw_if(!Arr::has($accessTokens, ['unionid', 'openid']), GQLException::class, '授权失败,请稍后再试!');

        //建立oauth关联
        $oAuth  = OAuth::firstOrNew(['oauth_type' => 'wechat', 'oauth_id' => $accessTokens['unionid']]);
        $openId = Arr::get($accessTokens, 'openid');
        if (isset($oAuth->id)) {
            $oAuthData = $oAuth->data;
            //存在open_id
            if (isset($oAuthData['openid'])) {
                $openId = Arr::get($oAuthData, 'openid');
                $wallet = $user->wallet;
                $payId  = $wallet->getPayId(Withdraw::WECHAT_PLATFORM);
                if (empty($payId)) {
                    $wallet->setPayId($openId, Withdraw::WECHAT_PLATFORM);
                    $wallet->save();

                    return $oAuth;
                } else {
                    throw new GQLException('您已授权成功,请勿重复授权!');
                }
            }
        }
        $oAuth->user_id = $user->id;
        $oAuth->data    = Arr::only($accessTokens, ['openid', 'refresh_token']);
        $oAuth->save();

        //同步钱包配置
        $wallet = $user->wallet;
        $wallet->setPayId($openId, Withdraw::WECHAT_PLATFORM);
        $wallet->save();

        return $oAuth;
    }

    public function alipay(User $user, $code)
    {
        throw_if(true, GQLException::class, '支付宝暂未开放,请稍后再试!');

        $userInfo = AlipayUtils::userInfo($code);
        $openId   = Arr::get($userInfo, 'user_id');
        throw_if(empty($openId), GQLException::class, '授权失败,请稍后再试!');

        $oauth = OAuth::firstOrNew(['oauth_type' => 'alipay', 'oauth_id' => $openId]);
        throw_if(isset($oauth->id), GQLException::class, '该支付宝已被绑定,请尝试其他账户!');

        //更新OAuth绑定
        $oauth->user_id = $user->id;
        $oauth->data    = $userInfo;
        $oauth->save();

        //更新钱包OPENID
        $wallet = $user->wallet;
        $wallet->setPayId($openId, Withdraw::ALIPAY_PLATFORM);
        $wallet->save();

        return $oauth;
    }
}

<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class WechatAppUtils
{
    protected $client = null;

    protected $config = [];

    public function __construct()
    {
        $this->config = config('wechat');
        $this->client = new Client(['time_out' => 5]);
    }

    /**
     * 获取微信用户access_token
     *
     * @param [String] $code
     * @return Array
     */
    public function accessToken($code)
    {
        $accessTokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';

        $response = $this->client->request('GET', $accessTokenUrl, [
            'query' => [
                'grant_type' => 'authorization_code',
                'code'       => $code,
                'appid'      => Arr::get($this->config, 'wechat_app.appid'),
                'secret'     => Arr::get($this->config, 'wechat_app.secret'),
            ],
        ]);

        $result = $response->getbody()->getContents();

        return empty($result) ? null : json_decode($result, true);
    }

    /**
     * 微信用户信息
     *
     * @param [String] $accessToken
     * @param [String] $openId
     * @return Array
     */
    public function userInfo($accessToken, $openId)
    {
        $userInfoUrl = 'https://api.weixin.qq.com/sns/userinfo';

        $response = $this->client->request('GET', $userInfoUrl, [
            'query' => [
                'access_token' => $accessToken,
                'openid'       => $openId,
                'lang'         => 'zh_CN',
            ],
        ]);

        $result = $response->getbody()->getContents();

        return empty($result) ? null : json_decode($result, true);
    }
}

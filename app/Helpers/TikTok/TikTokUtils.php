<?php
namespace App\Helpers\TikTok;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class TikTokUtils
{
    protected $client = null;

    protected $config = [];

    const BASE_URL = 'https://open.douyin.com';

    public function __construct()
    {
        $this->config = config('tiktok')[env('APP_NAME')];
        $this->client = new Client([
            'time_out' => $this->config['time_out'],
            'base_uri' => self::BASE_URL,
        ]);
    }

    /**
     * 获取抖音用户access_token
     *
     * @param [String] $code
     * @return Array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function accessToken($code)
    {
        $response = $this->client->request('GET', '/oauth/access_token/', [
            'query' => [
                'grant_type'    => 'authorization_code',
                'code'          => $code,
                'client_key'    => Arr::get($this->config, 'client_key'),
                'client_secret' => Arr::get($this->config, 'client_secret'),
            ],
        ]);

        $result = $response->getbody()->getContents();

        return empty($result) ? null : json_decode($result, true);
    }

    /**
     * 抖音用户信息
     *
     * @param [String] $accessToken
     * @param [String] $openId
     * @return Array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userInfo($accessToken, $openId)
    {
        $response = $this->client->request('GET', '/oauth/userinfo/', [
            'query' => [
                'access_token' => $accessToken,
                'open_id'      => $openId,
            ],
        ]);

        $result = $response->getbody()->getContents();

        return empty($result) ? null : json_decode($result, true);
    }

    /**
     * 抖音视频上传
     *
     * @param [String] $accessToken
     * @param [String] $openId
     * @return Array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function videoUpload($accessToken, $openId, $videoPath)
    {
        $response = $this->client->request('POST', '/video/upload/', [
            'query'     => [
                'access_token' => $accessToken,
                'open_id'      => $openId,
            ],
            'multipart' => [
                [
                    'name'     => 'video',
                    'contents' => file_get_contents($videoPath),
                ],
            ],
        ]);

        $result = $response->getbody()->getContents();

        return empty($result) ? null : json_decode($result, true);
    }

    /**
     * 创建抖音视频
     *
     * @param [String] $accessToken
     * @param [String] $openId
     * @param [String] $videoId
     * @return Array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createVideo($accessToken, $openId, $videoId)
    {
        $response = $this->client->request('POST', '/video/create/', [
            'query'       => [
                'access_token' => $accessToken,
                'open_id'      => $openId,
            ],
            'form_params' => [
                'video_id' => $videoId,
                'text'     => $text,
            ],
        ]);

        $result = $response->getbody()->getContents();

        return empty($result) ? null : json_decode($result, true);
    }
}

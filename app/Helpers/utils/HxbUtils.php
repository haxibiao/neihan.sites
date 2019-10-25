<?php

namespace App\Helpers;

use GuzzleHttp\Client;

/**
 * 哈希表主站相关工具类
 */
class HxbUtils
{
    /**
     * @Author      XXM
     * @DateTime    2018-10-18
     * @description [将GQL的行为记录到哈希表]
     * @param       [int]        $user_id        [用户id]
     * @param       [string]        $behavior       [行为]
     * @param       [int]        $behavior_id    [行为关联的id]
     * @param       [string]        $behavior_title [行为标题]
     * @return      [int]                        [http状态码]
     */
    public static function recordTaffic($user_id = null, $behavior = null, $behavior_id = null, $behavior_title = null)
    {
        $data = [
            'source_site' => env('APP_DOMAIN'),
            'ip'          => get_ip(),
            'device'      => device(),
            'device_os'   => platform(),
            'time'        => \Carbon\Carbon::now()->toDateTimeString(),
        ];
        if ($user_id) {
            $data['user_id'] = $user_id;
        }
        if ($behavior) {
            $data['behavior'] = $behavior;
        }
        if ($behavior_id) {
            $data['behavior_id'] = $behavior_id;
        }
        if ($behavior_title) {
            $data['behavior_title'] = $behavior_title;
        }
        $post_data = [
            'form_params' => ['data' => $data],
        ];
        //发起请求
        $client = new Client();
        try {
            $response = $client->post('http://haxibiao.com/api/traffic/app/send', $post_data);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getResponse()->getStatusCode();
        }

        return $response->getStatusCode();
    }

}

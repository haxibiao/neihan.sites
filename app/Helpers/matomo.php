<?php

function app_track_event($category, $action, $name = false, $value = false)
{
    $event['category'] = $category;
    $event['action']   = $action;
    $event['name']     = $name;
    $event['value']    = $value;

    $event = wrapMatomoEventData($event);

    //发送事件数据
    sendMatomoEvent($event);
}

function wrapMatomoEventData($event)
{
    $event['user_id'] = getUniqueUserId();
    $event['ip']      = getIp();

    //传给自定义变量 服务器
    $event['server'] = gethostname();

    // $event['dimension1'] = getOsSystemVersion(); //设备系统带版本
    // $event['dimension2'] = get_referer(); //下载渠道
    // $event['dimension3'] = getAppVersion(); //版本
    // $event['dimension4'] = getAppVersion() . "(build" . getAppBuild() . ")"; //热更新
    // $event['dimension5'] = User::categoryTag(); //新老用户分类
    // $event['dimension6'] = getDeviceBrand(); //用户机型品牌

    //比如：siteID 11 有剑气
    $event['siteId'] = env('MATOMO_SITE_ID');
    return $event;
}

function sendMatomoEvent(array $event)
{
    $event['cdt'] = time();
    try {
        $client = new \swoole_client(SWOOLE_SOCK_TCP); //同步阻塞？？
        //默认0.1秒就timeout, 所以直接丢给本地matomo:server
        $client->connect('127.0.0.1', env('MATOMO_PROXY_PORT')) or die("swoole connect failed\n");
        $client->set([
            'open_length_check'     => true,
            'package_length_type'   => 'n',
            'package_length_offset' => 0,
            'package_body_offset'   => 2,
        ]);
        $client->send(tcp_pack(json_encode($event)));
    } catch (\Throwable $ex) {
        return false;
    }
    return true;
}

function tcp_pack(string $data): string
{
    return pack('n', strlen($data)) . $data;
}
function tcp_unpack(string $data): string
{
    return substr($data, 2, unpack('n', substr($data, 0, 2), 0)[1]);
}

function app_track_goal($goal_id)
{
    if ($tracker = init_piwik_tracker()) {
        try
        {
            $tracker->doTrackGoal($goal_id);
        } catch (\Exception $ex) {
            Log::debug("app_track_goal:" . $ex->getMessage());
        }
    }
}

function app_track_user($action, $name = false, $value = false)
{
    app_track_event("后端事件", $action, $name, $value);
}

function app_track_post()
{
    app_track_user('发布动态');
}

function app_track_issue()
{
    app_track_user('发布视频问答');
}

function app_track_comment()
{
    app_track_user('发布评论');
}

function app_track_send_message()
{
    app_track_user('发送消息');
}

function app_track_launch()
{
    app_track_user('启动');
}

function app_track_app_download()
{
    app_track_user('App下载', 'app_download');
}

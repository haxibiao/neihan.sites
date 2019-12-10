<?php
use Illuminate\Support\Facades\Request;

function get_ip()
{
    $ip = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] as $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}

function random_str($length)
{
    return str_pad(mt_rand(0, 999999), $length, "0", STR_PAD_BOTH);
}

function ssl_url($url)
{
    if (starts_with($url, 'https')) {
        return $url;
    }
    if (!starts_with($url, 'http')) {
        return secure_url($url);
    }
    return str_replace("http", "https", $url);
}

function trim_https($url)
{
    //替换URL协议
    if (starts_with($url, 'https:')) {
        return  str_replace(['https:'], 'http:', $url);
    }
    return $url;
}

function get_domain_key()
{
    return str_replace('.', '_', get_domain());
}

function get_domain()
{
    $domain = str_replace('*.', '', Request::server('SERVER_NAME'));
    $domain = str_replace('l.', '', $domain);
    $domain = str_replace('www.', '', $domain);
    $domain = str_replace('staging.', '', $domain);
    return $domain;
}

function get_site_domain()
{
    return env('APP_DOMAIN', get_domain());
}

function fix_article_body_images($body)
{
    $preg = '/<img.*?src="(.*?)".*?>/is';

    preg_match_all($preg, $body, $match);

    if (!empty($match[1]) && !str_contains($body, 'haxibiao')) {
        foreach ($match[1] as $image_url) {
            $body = str_replace("$image_url", "https://haxibiao.com$image_url", $body);
        }
    }

    return $body;
}

function processVideo($video)
{
    $video->syncVodProcessResult();
    //如果还没有截图 就重新执行调用截图接口
    if (!$video->cover && !empty($video->qcvod_fileid)) {
        $duration = $video->duration > 9 ? 9 : $video->duration;
        \App\Helpers\QcloudUtils::makeCoverAndSnapshots($video->qcvod_fileid, $duration);
    }
}

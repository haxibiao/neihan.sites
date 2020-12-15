<?php

use GuzzleHttp\Client;

function GetURL($content)
{
    $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
    if (preg_match($regex, $content, $match)) {
        return $match[0];
    }
}

function getUrlContent($url)
{
    try {
        $client = new Client();
        $resp   = $client->get($url);

        $statusCode = $resp->getStatusCode();
        return [
            'content'    => $resp->getBody()->getContents(),
            'statusCode' => $statusCode,
        ];
    } catch (\Exception $th) {
        return [
            'content'    => null,
            'statusCode' => 404,
        ];
    }
}

function accessOK($url)
{
    $resp = getUrlContent($url);
    return in_array($resp['statusCode'], [200, 304]);
}

function tomorrow()
{
    return today()->tomorrow();
}


function CacheTodayCount($key, $count)
{
    if (\Cache::has($key)) {
        \Cache::increment($key, $count);
    } else {
        \Cache::add($key, $count, tomorrow());
    }
}

function redis()
{
    return app('redis.connection');
}

function cn2num($string)
{

    if (is_numeric($string)) {
        return $string;
    }
    // '仟' => '千','佰' => '百','拾' => '十',
    $string = str_replace('仟', '千', $string);
    $string = str_replace('佰', '百', $string);
    $string = str_replace('拾', '十', $string);
    $num    = 0;
    $wan    = explode('万', $string);
    if (count($wan) > 1) {
        $num += cn2num($wan[0]) * 10000;
        $string = $wan[1];
    }
    $qian = explode('千', $string);
    if (count($qian) > 1) {
        $num += cn2num($qian[0]) * 1000;
        $string = $qian[1];
    }
    $bai = explode('百', $string);
    if (count($bai) > 1) {
        $num += cn2num($bai[0]) * 100;
        $string = $bai[1];
    }
    $shi = explode('十', $string);
    if (count($shi) > 1) {
        $num += cn2num($shi[0] ? $shi[0] : '一') * 10;
        $string = $shi[1] ? $shi[1] : '零';
    }
    $ling = explode('零', $string);
    if (count($ling) > 1) {
        $string = $ling[1];
    }
    $d = array(
        '一' => '1', '二' => '2', '三' => '3', '四' => '4', '五' => '5', '六' => '6', '七' => '7', '八' => '8', '九' => '9',
        '壹' => '1', '贰' => '2', '叁' => '3', '肆' => '4', '伍' => '5', '陆' => '6', '柒' => '7', '捌' => '8', '玖' => '9',
        '零' => 0, '0'   => 0, 'O'   => 0, 'o'   => 0,
        '两' => 2,
    );
    return $num+@$d[$string];
}

<?php

namespace App\Helpers;

use Exception;

class DouyinSpider
{

    /**
     * 解析URL地址
     * param $url
     * return string
     */
    public function parse($url)
    {
        $html = $this->getCurl($url, 1);
        preg_match('#itemId: "(\d+)",#i', $html, $_id); //获取抖音连接ID
        preg_match('#dytk: "(.*?)"#i', $html, $_dytk); //获取抖音dytk
        if (!isset($_id[1]) or !isset($_dytk[1])) {
            throw new Exception('获取抖音链接id失败');
        }

        return $this->video_url($_id[1], $_dytk[1]);
    }

    /**
     *  获取抖音接口视频信息
     *  param $id  int
     *  param $dytk string
     **/
    private function video_url($id, $dytk)
    {

        $getApi = 'https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=' . $id . '&dytk=' . $dytk;
        $data   = $this->getCurl($getApi);
        $json   = json_decode($data, true);
        //视频描述
        $video         = [];
        $video['code'] = 1;
        foreach ($json['item_list'] as $k => $v) {
            //ID
            $list['aweme_id'] = $v['statistics']['aweme_id'];
            //视频描述
            $list['desc'] = $v['desc'];
            //评论数
            $list['comment_count'] = $v['statistics']['comment_count'];
            //点赞数
            $list['digg_count'] = $v['statistics']['digg_count'];
            //无水印URL
            $list['play_url'] = $this->Url($v['video']['play_addr']['url_list'][0]);
            $video[]          = $list;
        }

        return json_encode($video, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取重定向视频地址
     * param $url string
     * return string
     * */
    private function Url($url)
    {

        $data = $this->getCurl($url, 0);
        preg_match('#href="(.*?)">#i', $data, $video);
        return isset($video[1]) ? $video[1] : '解析失败';
    }

    private function getCurl($url, $foll = 0)
    {
        //初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //访问的url
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //完全静默
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //忽略https
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //忽略https
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25"]); //UA
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $foll); //默认为$foll=0,大概意思就是对照模块网页访问的禁止301 302 跳转。
        $output = curl_exec($ch); //获取内容
        curl_close($ch); //关闭
        return $output; //返回
    }

    private function getCurls($url, $post = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
}

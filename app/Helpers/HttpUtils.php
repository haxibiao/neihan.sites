<?php

namespace App\Helpers;

/**
 * 网络相关工具类
 */
class HttpUtils 
{
    /**
     * @Desc     获取http重定向之后的内容
     * @Author   czg
     * @DateTime 2018-06-21
     * @param    String     $url 起始地址
     * @return   [type]          重定向以后的内容
     */
    public static function getReal($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $a = curl_exec($ch);
        if(preg_match('#Location:(.*)#', $a, $r))
            return trim($r[1]);
        return null;
    }
}

<?php

namespace App\Helpers;

use App\Model;

/**
 * 图片操作工具类
 */
class ImageUtils 
{
    /**
     * @Desc     从字符串中提取img标签的src
     * 
     * @Author   czg
     * @DateTime 2018-06-21
     * @param    String     $content 被提取的字符串
     * @return   array      图片urls             
     */
    public static function getImageUrlFromHtml($content){
    	$pattern = "/<img.*?src=['|\"](.*?)['|\"].*?[\/]?>/iu";
        preg_match_all($pattern, $content, $matches);
        return end($matches);
    }
    /**
     * @Desc     获取base64编码的图片文件流
     * 
     * @Author   czg
     * @DateTime 2018-06-21
     * @return   [type]     文件流
     */
    public static function getBase64ImgStream(String $base64url){
    	$base64ImgData = str_after($base64url, 'base64,');
        return base64_decode($base64ImgData);
    }
}

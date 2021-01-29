<?php

namespace App;

use Haxibiao\Content\Article as BaseArticle;

class Article extends BaseArticle
{

    //getCoverAttribute方法重写，修复web首页来自哈希云的博客图片丢失问题
    public function getCoverAttribute()
    {
        $cover_url = $this->cover_path;
        //为空返回默认图片
        if (empty($cover_url)) {
            if ($this->type == 'article') {
                //返回null兼容has_image 等旧文章系统attrs的判断
                return null;
            }
            return url("/images/cover.png");
        }
        if (str_contains($cover_url, 'http')) {
            return $cover_url;
        }

        //文章的图片都应该已存cos,没有的修复文件+数据, 强制返回cdn全https url，兼容多端
        $cover_path = parse_url($cover_url, PHP_URL_PATH);
        return cdnurl($cover_path);
    }
}

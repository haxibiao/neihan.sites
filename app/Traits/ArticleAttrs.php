<?php

namespace App\Traits;

use App\Category;

trait ArticleAttrs
{
    //兼容title为空的post,videoPost等在旧web的显示article->title情况
    public function getSubjectAttribute()
    {
        if (!empty($this->title)) {
            return $this->title;
        }

        return str_limit($this->body);
    }

    public function getSubjectDescriptionAttribute()
    {
        if (!empty($this->description)) {
            return $this->description;
        }

        return $this->subject;
    }

    public function getSummaryAttribute()
    {
        $description = $this->description;
        if (empty($description) || strlen($description) < 2) {
            $body        = html_entity_decode($this->body);
            $description = str_limit(strip_tags($body), 130);
        }
        return str_limit($description, 130);
    }

    public function getUrlAttribute()
    {
        $path = '/%s/%d';
        if ($this->type == 'video') {
            return sprintf($path, $this->type, $this->video_id);
        }
        $path = sprintf($path, 'article', $this->id);
        return url($path); //TODO: 这里需要检查为何邮件的时候不返回full urll
    }

    public function hasImage()
    {
        return !empty($this->cover);
    }

    public function getPostTitle()
    {
        return $this->title ? $this->title : str_limit($this->body, $limit = 20, $end = '...');
    }

    public function getPivotCategoryAttribute()
    {
        return Category::find($this->pivot->category_id);
    }

    public function getPivotStatusAttribute()
    {
        return $this->pivot->submit;
    }

    public function getPivotTimeAgoAttribute()
    {
        return diffForHumansCN($this->pivot->created_at);
    }

    public function getCoversAttribute()
    {
        return $this->video ? $this->video->covers : null;
    }

    //兼容旧web api 的属性
    public function getHasImageAttribute()
    {
        return $this->image_id > 0 || !empty($this->cover);
    }

    public function getPrimaryImageAttribute()
    {
        return $this->cover;
    }

    //统一的文章封面逻辑，创建/更新时自动维护字段为path=（image中的一张或video的cover）
    //获取时自动返回full uri
    public function getCoverAttribute()
    {
        $cover_url = $this->cover_path;

        //有cos地址的直接返回
        if (str_contains($cover_url, 'cos')) {
            return $cover_url;
        }

        //TODO: 图片在本地？需要修复到cos
        if ($this->video) {
            if (!is_null($this->video->cover)) {

                $video_cover_path = parse_url($this->video->cover, PHP_URL_PATH);
                return \Storage::cloud()->url($video_cover_path);
            }
        }

        //为空返回默认图片
        if (empty($cover_url)) {
            return url("/images/cover.png");
        }

        //TODO: 剩余的保存fullurl的，需要修复为path, 同步image, video的　disk变化
        $path = parse_url($cover_url, PHP_URL_PATH);
        return \Storage::cloud()->url($path);
    }

    public function getVideoUrlAttribute()
    {
        return $this->video ? $this->video->url : null;
    }

    public function getLikedAttribute()
    {
        if ($user = getUser(false)) {
            return $like = $user->likedArticles()->where('liked_id', $this->id)->count() > 0;
        }
        return false;
    }

    public function getLikedIdAttribute()
    {
        if ($user = getUser(false)) {
            $like = $user->likedArticles()->where('liked_id', $this->id)->first();
            return $like ? $like->id : 0;
        }
        return 0;
    }

    public function getAnsweredStatusAttribute()
    {
        $issue = $this->issue;
        if (!$issue) {
            return null;
        }
        $resolutions = $issue->resolutions;
        if ($resolutions->isEmpty()) {
            return 0;
        }
        return 1;
    }

    public function getQuestionRewardAttribute()
    {
        if (!in_array($this->type, ['issue'])) {
            return 0;
        }
        return ($this->issue)->gold;
    }

    public function getScreenshotsAttribute()
    {
        $images      = $this->images;
        $screenshots = [];
        foreach ($images as $image) {
            $screenshots[] = ['url' => $image->url];
        }
        return json_encode($screenshots);
    }

    public function link()
    {
        //普通文章
        if ($this->type == 'article') {
            return $this->resoureTypeCN() . '<a href=' . $this->url . '>《' . $this->title . '》</a>';
        }
        //动态
        $title = str_limit($this->body, $limit = 50, $end = '...');
        if (empty($title)) {
            return '<a href=' . $this->url . '>' . $this->resoureTypeCN() . '</a>';
        }
        return $this->resoureTypeCN() . '<a href=' . $this->url . '>《' . $title . '》</a>';
    }
}

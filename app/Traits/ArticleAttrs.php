<?php

namespace App\Traits;

use App\Category;

trait ArticleAttrs
{
    public function getUrlAttribute()
    {
        $path = '/%s/%d';
        if ($this->type == 'video') {
            return sprintf($path, $this->type, $this->video_id);
        }
        if ($this->type == 'post') {
            return sprintf($path, 'article', $this->id);
        }
        $path = sprintf($path, $this->type, $this->id);
        return secure_url($path); //TODO: 这里需要检查为何邮件的时候不返回full urll
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

    public function getCoverAttribute()
    {
        $cover_url = $this->image_url;

        //有cos地址的直接返回
        if (str_contains($cover_url, env('COS_DOMAIN'))) {
            return $cover_url;
        }

        //兼容vod
        if (str_contains($cover_url, ['vod2.'])) {
            return $cover_url;
        }

        //TODO: 图片在本地？需要修复到cos
        if ($this->video()->exists()) {
            if (!is_null($this->video->cover)) {
                return \Storage::cloud()->url($this->video->cover);
            }
        }

        //为空返回默认图片
        if (empty($cover_url)) {
            return \Storage::cloud()->url("/images/cover.png");
        }

        //TODO: 剩余的保存fullurl的，需要修复为path, 同步image, video的　disk变化
        $path = parse_url($cover_url, PHP_URL_PATH);
        return \Storage::cloud()->url($path);
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
    public function getCategoryAttribute()
    {
        return $this->category()->first();
    }

    public function getCategoriesAttribute()
    {
        return $this->hasCategories()->get();
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
}

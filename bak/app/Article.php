<?php

namespace App;

use App\Model;
use App\Traits\ArticleRelation;

class Article extends Model
{
    use ArticleRelation;

    protected $fillable = [
        'title',
        'keywords',
        'description',
        'author',
        'author_id',
        'user_name',
        'user_id',
        'category_id',
        'body',
        'image_url',
        'is_top',
        'status',
        'source_url'
    ];

    protected $dates = ['edited_at', 'delay_time'];

    protected $touches = ['category', 'categories', 'collections'];

    //计算用方法

    public function description()
    {
        $description = empty($this->description) ? str_limit(strip_tags($this->body), 200) : str_limit($this->description, 200);
        $description = html_entity_decode($description);
        return $description;
    }

    public function introduction()
    {
        $description = empty($this->description) ? str_limit(strip_tags($this->body), 200) : str_limit($this->description, 200);
        return $description;
    }

    public function primaryImage()
    {
        $image_url_path = parse_url($this->image_url, PHP_URL_PATH);
        $image          = Image::firstOrNew([
            'path' => $image_url_path,
        ]);
        if (str_contains($this->image_url, "haxibiao")) {
            return $this->image_url;
        }

        return $image->path_small();
    }

    public function hasImage()
    {
        $image_url_path = parse_url($this->image_url, PHP_URL_PATH);
        $image          = Image::firstOrNew([
            'path' => $image_url_path,
        ]);

        if (str_contains($this->image_url, "haxibiao")) {
            return 1;
        }
        return $image->id;
    }

    public function fillForJs()
    {
        $this->time_ago      = $this->timeAgo();
        $this->has_image     = $this->hasImage();
        $this->primary_image = $this->primaryImage();
    }

    public function isSelf()
    {
        return Auth::check() && Auth::id() == $this->user_id;
    }

    public function link()
    {
        return '<a href="/article/' . $this->id . '">' . $this->title . '</a>';
    }

    public function image_top()
    {
        $image_url_path = parse_url($this->image_url, PHP_URL_PATH);
        $image_url_path = str_replace('.small', '', $image_url_path);
        $image          = Image::firstOrNew([
            'path' => $image_url_path,
        ]);

        return $image->path_top();
    }

    public function author_comments()
    {
        return Comment::with('user')->where('commentable_type', 'articles_author')->get();
    }
}

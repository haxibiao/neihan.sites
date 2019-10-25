<?php

namespace App;

//TODO:  resolution 改名为 solution
class Resolution extends Model
{
    public $fillable = [
        'issue_id',
        'user_id',
        'article_id',
        'answer',
        'image_url',
        'count_likes',
    ];

    protected $touches = ['issue'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function issue()
    {
        return $this->belongsTo(\App\Issue::class);
    }

    public function article()
    {
        return $this->belongsTo(\App\Article::class);
    }

    public function haveImage()
    {
        return !empty($this->image_url);
    }

    public function primaryImage()
    {
        $image_url = parse_url($this->image_url, PHP_URL_PATH);
        if (!str_contains($image_url, '.small.')) {
            $image = Image::firstOrNew([
                'path' => $image_url,
            ]);
            if ($image) {
                $image_url = $image->thumbnail;
            }
        }
        return $image_url;
    }

    public function shortText()
    {
        //回答的前200
        $text = strip_tags($this->answer);
        $text = str_limit($text, 200);
        if (strlen($text) < 200) {
            //回答不够文字，如果有关联文章，取文章的正文...
            if ($this->article) {
                $more_text = str_limit(strip_tags($this->article->body), 200 - strlen($text));
                $text = $text . $more_text;
            }
        }
        return $text;
    }
}

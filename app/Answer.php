<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'article_id',
        'answer',
    ];

    protected $touches = ['question'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function question()
    {
        return $this->belongsTo(\App\Question::class);
    }

    public function article()
    {
        return $this->belongsTo(\App\Article::class);
    }

    public function haveImage()
    {
        return !empty($this->image_url);
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
                $text      = $text . $more_text;
            }
        }
        return $text;
    }
}

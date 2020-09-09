<?php

namespace App;

use App\Model;
use Haxibiao\Sns\Traits\CanBeFollow;

class Collection extends Model
{
    use CanBeFollow;
    public $fillable = [
        'user_id',
        'status',
        'type',
        'name',
        'logo',
        'count_words',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function articles()
    {
        return $this->hasMany(\App\Article::class);
    }

    public function hasManyArticles()
    {
        return $this->hasMany(\App\Article::class)->where('status', '>=', '0');
    }

    public function publishedArticles()
    {
        return $this->hasMany(\App\Article::class)->where('status', '>=', '0');
    }

    public function logo()
    {
        $path = empty($this->logo) ? '/images/collection.png' : $this->logo;
        if (file_exists(public_path($path))) {
            return $path;
        }
        return env('APP_URL') . $path;
    }
}

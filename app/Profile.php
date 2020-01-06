<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'qq',
        'introduction',
        'json',
        'count_articles',
        'count_likes',
        'count_follows',
        'count_followings',
        'count_words',
        'count_collections',
        'count_favorites',
        'count_actions',
        'count_reports',
        'count_contributes',
        'enable_tips',
        'tip_words',
        'gender',
        'website',
        'qrcode',
        'age',
        'source',
        'birthday',
        'questions_count',
        'answers_count',
        'correct_count',
        'app_version'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class);
    }
}

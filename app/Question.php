<?php

namespace App;

use App\Traits\QuestionResolvers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Question extends Model
{
    use QuestionResolvers;

    protected $fillable = [
        'description',
        'selections',
        'answer',
        'gold',
        'ticket',
        'image_id',
        'category_id',
        'type',
        'correct_count',
        'wrong_count',
        'user_id',
        'submit',
        'video_id',
        'rank',
        'count_comments',
        'count_likes',
        'answers_count',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($question) {
            $question->syncType();
        });
    }


    //最大回答次数
    const MAX_ANSWERS_COUNT = 3;

    //默认奖励
    const DEFAULT_GOLD   = 3;
    const DEFAULT_TICKET = 1;

    //提交状态
    const DELETED_SUBMIT   = -4; //已删除
    const CANCELLED_SUBMIT = -3; //草稿箱（暂存，已撤回）
    const REFUSED_SUBMIT   = -2; //已拒绝
    const REMOVED_SUBMIT   = -1; //已移除
    const REVIEW_SUBMIT    = 0; //待审核
    const SUBMITTED_SUBMIT = 1; //已收录

    //问题类型
    const IMAGE_TYPE = 1;
    const VIDEO_TYPE = 2;
    const TEXT_TYPE  = 0;

    public function likes(): MorphMany
    {
        return $this->morphMany(\App\Like::class, 'likable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(\App\Comment::class, 'commentable');
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(\App\Video::class, 'video_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Category::class, 'category_id');
    }

    public function syncType()
    {
        $type = self::TEXT_TYPE;

        if (!is_null($this->image_id)) {
            $type = self::IMAGE_TYPE;
        }

        if (!is_null($this->video_id)) {
            $type = self::VIDEO_TYPE;
        }

        $this->type = $type;
    }

    public function getSelectionsAttribute($value)
    {
        $value = trim($value, '"');
        $value = str_replace('\\', '', $value);
        return json_decode($value, true);
    }

    public function checkAnswer($answer)
    {
        $answer = trim($answer);
        return strtoupper($this->answer) == strtoupper($answer);
    }
}

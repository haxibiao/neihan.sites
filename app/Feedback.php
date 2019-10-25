<?php

namespace App;

use App\Traits\FeedbackResolvers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Feedback extends Model
{
    use FeedbackResolvers;

    protected $fillable = [
        'user_id',
        'content',
        'contact',
    ];

    protected $casts = [
        'top_at' => 'datetime',
    ];

    // 待处理
    const STATUS_PENDING = 0;

    // 已驳回
    const STATUS_REJECT = 1;

    // 已处理
    const STATUS_PROCESSED = 2;

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(\App\Image::class)->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, 'visited');
    }

    //attrs TODO: move out

    public function getStatusMsgAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return '待处理';
                break;
            case self::STATUS_REJECT:
                return '已驳回';
                break;
            case self::STATUS_PROCESSED:
                return '已处理';
                break;
        }
    }

    public function getHotAttribute()
    {
        if ($user = getUser(false)) {
            Visit::firstOrCreate([
                'user_id' => $user->id,
                'visited_type' => 'feedbacks',
                'visited_id' => $this->id,
            ]);
        }
        $comment = $this->comments()->count();
        return $comment * 20 + $this->visits()->count();
    }

    public function getCountCommentAttribute()
    {
        return $this->comments->count();
    }

    //TODO: 这个方法对应的user story 要重构
    public function getImageItem($number = 0)
    {
        $image_item = $this->images->isNotEmpty() ? $this->images->get($number) : null;
        return $image_item;
    }

    public function getImageItemUrl($number = 0)
    {
        if ($this->images->isNotEmpty()) {
            return empty($this->images->get($number)) ? "" : $this->images->get($number)->url;
        }
        return "";
    }
}

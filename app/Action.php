<?php

namespace App;

use App\Model;

class Action extends Model
{
    public $fillable = [
        'user_id',
        'actionable_type',
        'actionable_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function actionable()
    {
        return $this->morphTo();
    }

    public function fillForJs()
    {
        $this->user->fillForJs();
        if (empty($this->actionable)) {
            return;
        }
        switch (get_class($this->actionable)) {
            case 'App\Article':
                $this->actionable->fillForJs();
                break;
            case 'App\Comment':
                $this->load('actionable.commentable.user');
                $this->actionable->commentable->contentUrl = $this->actionable->commentable->url;
                break;
            case 'App\Favorite':
                $this->load('actionable.faved.user');
                break;
            case 'App\Like':
                $this->load('actionable.liked.user');
                break;
            case 'App\Follow':
                if (get_class($this->actionable->followed) == 'App\Category') {
                    $this->load('actionable.followed.user');
                    $catgory                     = $this->actionable->followed;
                    $this->actionable->is_follow = is_follow('categories', $catgory->id);
                } else {
                    $this->load('actionable.followed');
                    $user                        = $this->actionable->followed;
                    $this->actionable->is_follow = is_follow('users', $user->id);
                }
                $this->actionable->followed->fillForJs();
                break;
        }
        return $this;
    }

    public static function createAction($type, $id, $userId)
    {
        return Action::create([
            'actionable_type' => $type,
            'actionable_id'   => $id,
            'user_id'         => $userId,
        ]);
    }
}

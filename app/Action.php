<?php

namespace App;

use App\Model;

class Action extends Model
{
    public $fillable = [
        'user_id',
        'actionable_type',
        'actionable_id',
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
                $this->actionable->image_url = $this->actionable->primaryImage();
                break;
            case 'App\Comment':
                $this->load('actionable.commentable.user');
                $this->actionable->commentable->image_url = $this->actionable->commentable->primaryImage();
                $this->actionable->commentable->contentUrl = $this->actionable->commentable->content_url();
                break;
            case 'App\Favorite': 
                $this->load('actionable.faved.user');
                break;
            case 'App\Like':
                $this->load('actionable.liked.user');
                if(get_class($this->actionable->liked) == 'App\Article'){
                    $article = $this->actionable->liked;
                    $article->image_url = $article->primaryImage();
                }else if(get_class($this->actionable->liked) == 'App\Comment'){
                    $comment = $this->actionable->liked;
                    $article = $comment->commentable;
                    $article->image_url = $article->primaryImage();
                }
                break;
            case 'App\Follow':
                if (get_class($this->actionable->followed) == 'App\Category') {
                    $this->load('actionable.followed.user');
                    $catgory = $this->actionable->followed;
                    $this->actionable->is_follow = is_follow('categories', $catgory->id);
                }else{
                    $this->load('actionable.followed');
                    $user = $this->actionable->followed;
                    $this->actionable->is_follow = is_follow('users',$user->id);
                }
                $this->actionable->followed->fillForJs();
                break;
        }
        return $this;
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\NewLike'    => [
            'App\Listeners\SendNewLikeNotification',
        ],
        'App\Events\NewFollow'  => [
            'App\Listeners\SendNewFollowNotification',
        ],
        'App\Events\NewComment' => [
            'App\Listeners\SendNewCommentNotification',
        ],
        'App\Events\NewMessage' => [
            'App\Listeners\SendNewMessageNotification',
        ],
    ];
    /**
     * 消息订阅者，
     * @var [type]
     */
    protected $subscribe = [

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \App\Message::observe(\App\Observers\MessageObserver::class);
        \App\Comment::observe(\App\Observers\CommentObserver::class);
        \App\Like::observe(\App\Observers\LikeObserver::class);
        \App\Article::observe(\App\Observers\ArticleObserver::class);
        \App\Follow::observe(\App\Observers\FollowObserver::class);
    }
}

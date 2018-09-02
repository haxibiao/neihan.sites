<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\CommentWasCreated' => [
            'App\Listeners\NewComment',  
        ],
        'App\Events\CommentWasDeleted' => [
            'App\Listeners\DestroyedComment',
        ],

        'App\Events\LikeWasCreated' => [
            'App\Listeners\NewLike',  
        ],
        'App\Events\LikeWasDeleted' => [
            'App\Listeners\DestroyedLike',
        ],

        'App\Events\FavoriteWasCreated' => [
            'App\Listeners\NewFavorite',  
        ],
        'App\Events\FavoriteWasDeleted' => [
            'App\Listeners\DestroyedFavorite',
        ],

        'App\Events\FollowWasCreated' => [
            'App\Listeners\NewFollow',  
        ],
        'App\Events\FollowWasDeleted' => [
            'App\Listeners\DestroyedFollow',
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

        //
    }
}

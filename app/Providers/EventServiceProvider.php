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

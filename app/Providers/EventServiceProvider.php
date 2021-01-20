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

    ];
    /**
     * 消息订阅者，
     * @var [type]
     */
    protected $subscribe = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        if (config('database.debug')) {
            $this->listen['Illuminate\Database\Events\QueryExecuted'] = [
                'App\Listeners\QueryListener',
            ];
        }

        parent::boot();

    }
}

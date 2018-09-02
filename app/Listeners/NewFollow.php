<?php

namespace App\Listeners;

use App\Events\FollowWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewFollow
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FollowWasCreated  $event
     * @return void
     */
    public function handle(FollowWasCreated $event)
    {
        //
    }
}

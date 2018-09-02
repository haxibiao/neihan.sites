<?php

namespace App\Listeners;

use App\Events\FollowWasDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DestroyedFollow
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
     * @param  FollowWasDeleted  $event
     * @return void
     */
    public function handle(FollowWasDeleted $event)
    {
        //
    }
}

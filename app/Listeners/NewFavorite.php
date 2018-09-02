<?php

namespace App\Listeners;

use App\Events\FavoriteWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewFavorite
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
     * @param  FavoriteWasCreated  $event
     * @return void
     */
    public function handle(FavoriteWasCreated $event)
    {
        //
    }
}

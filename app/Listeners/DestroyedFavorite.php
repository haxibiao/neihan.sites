<?php

namespace App\Listeners;

use App\Events\FavoriteWasDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DestroyedFavorite
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
     * @param  FavoriteWasDeleted  $event
     * @return void
     */
    public function handle(FavoriteWasDeleted $event)
    {
        //
    }
}

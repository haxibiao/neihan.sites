<?php

namespace App\Listeners;

use App\Events\NewReport;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewReportNotification implements ShouldQueue
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
     * @param  NewReport  $event
     * @return void
     */
    public function handle(NewReport $event)
    {
        //
    }
}

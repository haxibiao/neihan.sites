<?php

namespace App\Listeners;

class QueryListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //db raw 跟 vsprintf 格式化字符冲突
        $sql = str_replace("?", "'%s'", $event->sql);
        try {
            $sql = vsprintf($sql, $event->bindings);
        } catch (\Exception $ex) {
            $sql = $event->sql;
        }
        info($sql);
    }
}

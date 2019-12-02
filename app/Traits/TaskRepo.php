<?php

namespace App\Traits;

use Carbon\Carbon;

trait TaskRepo
{

    public function getDailyStartTime()
    {
        $start_at       = $this->start_at;
        $start_date     = Carbon::parse($start_at);
        $start_date_day = $start_date->addDays($start_date->diffInDays(Carbon::tomorrow()));

        if (empty($start_at)) {
            $start_date_day = Carbon::today();
        }
        return $start_date_day;
    }

    public function getDailyEndTime()
    {
        $end_at       = $this->end_at;
        $end_date     = Carbon::parse($end_at);
        $end_date_day = $end_date->addDays($end_date->diffInDays(Carbon::tomorrow()));

        if (empty($end_at)) {
            $end_date_day = Carbon::tomorrow();
        }

        return $end_date_day;
    }
}

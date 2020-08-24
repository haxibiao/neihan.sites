<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait VisitAttrsCache
{
    public function getArticleCache()
    {
        return $this->visited instanceof \App\Article ? $this->visited : null;
    }

    public function getTypeCache()
    {
        return str_singular($this->visited_type);
    }

    public function getTimeAgoCache()
    {
        $last_datetime = $this->updated_at;
        $carbon        = Carbon::createFromFormat('Y-m-d H:i:s', $last_datetime);
        if ($carbon->isToday()) {
            return str_replace(" ", "", diffForHumansCN($this->updated_at));
        }
        return date("m-d H:i", strtotime($this->updated_at));
    }
}

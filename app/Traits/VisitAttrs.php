<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait VisitAttrs
{
    public function getArticleAttribute()
    {
        return $this->visited instanceof \App\Post ? $this->visited : null;
    }

    public function getPostAttribute()
    {
        return $this->visited instanceof \App\Post ? $this->visited : null;
    }

    public function getTypeAttribute()
    {
        return str_singular($this->visited_type);
    }

    public function getTimeAgoAttribute()
    {
        $last_datetime = $this->updated_at;
        $carbon        = Carbon::createFromFormat('Y-m-d H:i:s', $last_datetime);
        if ($carbon->isToday()) {
            return str_replace(" ", "", diffForHumansCN($this->updated_at));
        }
        return date("m-d H:i", strtotime($this->updated_at));
    }
}

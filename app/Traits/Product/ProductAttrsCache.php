<?php

namespace App\Traits;

trait ProductAttrsCache
{
    public function getCoverCache()
    {
        // dd($this->image()->first());
        return !is_null($this->image()->first()) ? $this->image()->first() : null;
    }
}

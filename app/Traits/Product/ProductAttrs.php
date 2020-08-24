<?php

namespace App\Traits;

trait ProductAttrs
{
    public function getCoverAttribute()
    {
        // dd($this->image()->first());
        return !is_null($this->image()->first()) ? $this->image()->first() : null;
    }
}

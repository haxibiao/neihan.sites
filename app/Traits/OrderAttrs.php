<?php

namespace App\Traits;

trait OrderAttrs
{
    public function getPasswordAttribute()
    {
        return !is_null($this->platformAccount()->first()) ? $this->platformAccount()->first()->password : null;
    }
    public function getAccountAttribute()
    {
        return !is_null($this->platformAccount()->first()) ? $this->platformAccount()->first()->account : null;
    }
}

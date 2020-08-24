<?php

namespace App\Traits;

trait OrderAttrsCache
{
    public function getPasswordCache()
    {
        return !is_null($this->platformAccount()->first()) ? $this->platformAccount()->first()->password : null;
    }
    public function getAccountCache()
    {
        return !is_null($this->platformAccount()->first()) ? $this->platformAccount()->first()->account : null;
    }
}

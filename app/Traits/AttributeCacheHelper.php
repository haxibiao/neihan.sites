<?php

namespace App\Traits;

trait AttributeCacheHelper
{
    private $cachedAttributes = [];


    public function getCachedAttribute(string $key, callable $callable)
    {
        if (!array_key_exists($key, $this->cachedAttributes)) {
            $this->setCachedAttribute($key, call_user_func($callable));
        }

        return $this->cachedAttributes[$key];
    }

    public function setCachedAttribute(string $key, $value)
    {
        return $this->cachedAttributes[$key] = $value;
    }

    public function refresh()
    {
        unset($this->cachedAttributes);

        return parent::refresh();
    }
}

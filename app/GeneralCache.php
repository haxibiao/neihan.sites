<?php

namespace App;

use Illuminate\Support\Str;

trait GeneralCache
{
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * 所有attribute全走 general 通过general来调用对应的缓存方法
     */
    protected function mutateAttribute($key, $value)
    {
        $attributeKey = 'general';
        return $this->{'get' . Str::studly($attributeKey) . 'Attribute'}($key);
    }

    public function hasGetMutator($key)

    {
        $cacheKey = $this->getGeneralKey($key);
        $simpleKey = $this->getSimpleKey($key);
        if (!array_key_exists($key, $this->attributes) && (method_exists(static::class, $cacheKey) || method_exists(static::class, $simpleKey))) {
            return true;
        }

        return false;
    }

    public function getGeneralAttribute($key)
    {

        //cache attribute
        $cacheKey = $this->getGeneralKey($key);
        if (method_exists(static::class, $cacheKey)) {
            $callable = [$this, $cacheKey];
            return $this->getCachedAttribute($cacheKey, $callable);
        }

        //simple attribute
        $simpleKey = $this->getSimpleKey($key);
        if (method_exists(static::class, $simpleKey)) {
            return $this->SimpleAttribute($key);
        }


        //return $this->getRelationValue($key);
    }

    protected function getGeneralKey($key)
    {
        return 'get' . Str::studly($key) . 'Cache';
    }

    protected function getSimpleKey($key)
    {
        return  'get' . Str::studly($key) . 'Attribute';
    }

    protected function SimpleAttribute($key)
    {
        // $this->{'get' . Str::studly($key) . 'Attribute'}
        return $this->{$this->getSimpleKey($key)}();
    }
}

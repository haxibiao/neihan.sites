<?php

namespace App\Traits;

trait VideoAttrs
{
    public function getCoversAttribute()
    {
        return $this->jsonData('covers');
    }

    public function getInfoAttribute()
    {
        if($this->json){
            return null;
        }
        return json_decode($this->json);
    }

    public function getUrlAttribute()
    {
        if (starts_with($this->path, 'http')) {
            return $this->path;
        }
        $localFileExist = !is_prod() && \Storage::disk('public')->exists($this->path);
        if ($localFileExist) {
            return env('LOCAL_APP_URL') . '/storage/' . $this->path;
        }
        return \Storage::disk('cosv5')->url($this->path);
    }
}

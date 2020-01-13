<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait VideoAttrs
{
    public function getCoversAttribute()
    {
        return $this->jsonData('covers');
    }

    public function getCoverUrlAttribute()
    {
        $cover = $this->cover;
        if (Str::contains($cover, 'hashvod') && Str::contains($cover, 'http')) {
            return $cover;
        }

        //TODO: 修复数据，数据库统一存path
        $coverPath = parse_url($cover, PHP_URL_PATH);
        return cdnurl($coverPath);
    }

    public function getInfoAttribute()
    {
        $json = json_decode($this->json, true);

        // 相对路径 转 绝对路径
        $data = [
            'cover'  => \Storage::cloud()->url($json['cover'] ?? '/images/cover.png'),
            'width'  => $json['width']?? null,
            'height' => $json['height']?? null,
        ];

        return $data;
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

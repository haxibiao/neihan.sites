<?php

namespace App;

use Haxibiao\Content\Collection as BaseCollection;
use Haxibiao\Sns\Traits\CanBeFollow;
use Illuminate\Support\Facades\Storage;

class Collection extends BaseCollection
{
    use CanBeFollow;

    public function saveDownloadImage($file)
    {
        if ($file) {
            $cover = '/collect' . $this->id . '_' . time() . '.png';
            $cosDisk   = Storage::cloud();
            $cosDisk->put($cover, \file_get_contents($file->path()));

            return Storage::cloud()->url($cover);;
        }
    }
}

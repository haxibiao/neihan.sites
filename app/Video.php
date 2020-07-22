<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Haxibiao\Media\Video as BaseVideo;

class Video extends BaseVideo
{

    function createdAt()
    {
        return diffForHumansCN($this->created_at);
    }

    public function isSelf()
    {
        return Auth::check() && Auth::id() == $this->id;
    }
}

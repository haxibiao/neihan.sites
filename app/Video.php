<?php

namespace App;

use Haxibiao\Media\Video as BaseVideo;

class Video extends BaseVideo
{

    function createdAt()
    {
        return diffForHumansCN($this->created_at);
    }
}

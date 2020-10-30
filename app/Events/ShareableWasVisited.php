<?php

namespace App\Events;

use App\Share;

/**
 * 分享对象被成功的访问到
 */
class ShareableWasVisited
{
    public $share;

    public function __construct(Share $share)
    {
        $this->share = $share;
    }
}
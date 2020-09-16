<?php
namespace App\Traits;

use App\Like;

trait CommentAttrs
{
    public function getRepliesAttribute()
    {
        return $this->replyComments()->latest('id')->take(20)->get();
    }
}

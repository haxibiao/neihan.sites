<?php

namespace App\Traits;

trait TimeAgo
{
    function timeAgo()
    {
        return diffForHumansCN($this->created_at);
    }

    function createdAt()
    {
        return diffForHumansCN($this->created_at);
    }

    function updatedAt()
    {
        return diffForHumansCN($this->updated_at);
    }

    function editedAt()
    {
        return diffForHumansCN($this->edited_at);
    }
}

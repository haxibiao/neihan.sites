<?php
namespace App\Traits;

use App\Share;

trait Shareable
{
    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }
}
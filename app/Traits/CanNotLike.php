<?php


namespace App\Traits;


use App\NotLike;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanNotLike
{
    public function notLikes(): HasMany
    {
        return $this->hasMany(NotLike::class);
    }
}
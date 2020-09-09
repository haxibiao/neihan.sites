<?php

namespace App;

use App\Traits\NotLikeRepo;
use App\Traits\NotLikeResolvers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotLike extends Model
{
    use NotLikeRepo, NotLikeResolvers;

    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function notLikeable(): morphTo
    {
        return $this->morphTo();
    }

    public function scopeByType($query, $value)
    {
        return $query->where('not_likable_type', $value);
    }

    public function scopeByNotLikableId($query, $id)
    {
        return $query->where('not_likable_id', $id);
    }

}

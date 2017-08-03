<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = [
        'user_id',
        'object_id',
        'type',
        'body',
        'comment_id',
    ];

    public function comment()
    {
        return $this->belongsTo(App\Comment::class);
    }
}

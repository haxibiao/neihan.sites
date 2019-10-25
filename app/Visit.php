<?php

namespace App;

use App\Traits\VisitAttrs;
use App\Traits\VisitResolvers;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use VisitResolvers;
    use VisitAttrs;

    protected $fillable = [
        'user_id',
        'visited_id',
        'visited_type',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function visited()
    {
        return $this->morphTo();
    }

    public static function createVisit($user_id, $visited_id, $visited_type)
    {
        return Visit::firstOrCreate([
            'user_id'      => $user_id,
            'visited_id'   => $visited_id,
            'visited_type' => $visited_type,
        ]
        );
    }

    public static function saveListedVideos($user, $videoArticles)
    {
        foreach ($videoArticles as $article) {
            self::createVisit($user->id, $article->video_id, 'videos');
        }
    }
}

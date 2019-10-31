<?php

namespace App\Traits;

use App\Visit;

trait VisitRepo
{
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

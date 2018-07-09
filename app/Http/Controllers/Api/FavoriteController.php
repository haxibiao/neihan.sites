<?php

namespace App\Http\Controllers\Api;

use App\Favorite;
use App\Article;
use App\Video;
use App\Action;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\ArticleFavorited;

class FavoriteController extends Controller
{
    public function toggle(Request $request, $id, $type)
    {
        $user   = $request->user();
        $result = 0;
        $favorite = Favorite::firstOrNew([
            'user_id'       => $user->id,
            'faved_id'   => $id,
            'faved_type' => get_polymorph_types($type),
        ]);
        if ($favorite->id) {
            $favorite->delete();
        } else {
            $favorite->save();
            $result = 1;

            //record action
            $action = Action::firstOrNew([
                'user_id'         => $user->id,
                'actionable_type' => 'favorites',
                'actionable_id'   => $favorite->id,
            ]);
            $action->save();
            
            //发送通知
            $article = $favorite->faved; 
            $article->user->notify(new ArticleFavorited($article, $user));  
        }

        $user->count_favorites = $user->favorites()->count();
        $user->save();

        return $result;
    }

    public function get(Request $request, $id, $type)
    {
        $favorite = Favorite::firstOrNew([
            'user_id'   => $request->user()->id,
            'faved_id' => $id,
            'faved_type'      => $type,
        ]);
        return $favorite->id;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\Article;
use App\Http\Controllers\Controller;
use App\Like;
use App\Notifications\ArticleLiked;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LikeController extends Controller
{
    public function toggle(Request $request, $id, $type)
    {
        $like = new Like();
        $user = $request->user();
        $data = [
            'user_id'   =>  $user->id,
            'liked_id'  =>  $id,
            'liked_type'=>  str_plural($type)
        ];
        $like->toggleLike($data);
        return $like->likeUsers($data);
    }

    public function getForGuest(Request $request, $id, $type)
    {
        return $this->get($request, $id, $type); 
    }

    public function get(Request $request, $id, $type)
    {
        $like = new Like();
        $data = [
            'liked_id'  =>  $id,
            'liked_type'=>  str_plural($type)
        ];
        return $like->likeUsers($data);
    }
}

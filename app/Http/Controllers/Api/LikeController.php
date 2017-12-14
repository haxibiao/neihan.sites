<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\Article;
use App\Http\Controllers\Controller;
use App\Like;
use App\Video;
use App\Notifications\ArticleLiked;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, $id, $type)
    {
        $user = $request->user();
        $data = [
            'user_id'    => $user->id,
            'liked_id'   => $id,
            'liked_type' => get_polymorph_types($type),
        ];
        $like = Like::firstOrNew($data);
        if ($like->id) {
            $like->delete();
            $this->notify($type, $id, -1);
        } else {
            $like->save();

            $this->notify($type, $id, 1);

            //record action
            $action = Action::firstOrNew([
                'user_id'         => $user->id,
                'actionable_type' => 'likes',
                'actionable_id'   => $like->id,
            ]);
            $user->actions()->save($action);
        }
        return $this->get($request, $id, $type);
    }

    public function get(Request $request, $id, $type)
    {
        if ($request->user()) {
            $like = Like::firstOrNew([
                'user_id'    => $request->user()->id,
                'liked_id'   => $id,
                'liked_type' => get_polymorph_types($type),
            ]);
            $data['is_liked'] = $like->id;
        }
        $data['likes'] = 0;
        if ($type == 'article') {
            $article       = Article::findOrFail($id);
            $data['likes'] = $article->count_likes;
        }
        if ($type == 'video') {
            $video         = Video::findOrFail($id);
            $data['likes'] = $video->count_likes;
        }
        return $data;
    }

    public function notify($type, $id, $num)
    {
        if ($type == 'article') {
            $article = Article::with('user')->findOrFail($id);
            $article->count_likes += $num;
            $article->save();

            $author              = $article->user;
            $author->count_likes = $author->count_likes + $num;
            $author->save();

            if ($num > 0) {
                $user = request()->user();
                $author->notify(new ArticleLiked($article->id, $user->id));
                $author->forgetUnreads();
            }

        }
        if ($type == 'video') {
            $video = Video::with('user')->findOrFail($id);
            $video->count_likes += $num;
            $video->save();
        }
    }
}

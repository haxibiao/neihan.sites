<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    public function save(Request $request)
    {
        $comment          = new Comment($request->all());
        $comment->user_id = $request->user()->id;
        $comment->lou     = Comment::where('object_id', $request->get('object_id'))->where('type', $request->get('type'))->count() + 1;
        $comment->save();
        return $comment;
    }

    public function get(Request $request, $id, $type)
    {
        $comments = Comment::with('user')->with('comment.user')
            ->orderBy('lou')
            ->where('type', $type)
            ->where('object_id', $id)
            ->paginate(5);
        foreach ($comments as $comment) {
            $comment->created_at_cn = diffForHumansCN($comment->created_at);
            $comment->user->picture = get_avatar($comment->user);
        }

        return $comments;
    }

    public function like(Request $request, $id)
    {
        //use cache check if like or unlike
        $cache = Cache::get('like_comment_' . $id);
        if (empty($cache)) {
            Cache::put('like_comment_' . $id, 1, 60 * 24);
        }
        $liked = !empty($cache) && $cache;
        if($liked) {
            Cache::put('like_comment_' . $id, 0, 60 * 24);
        }
        $comment        = Comment::find($id);
        $comment->likes = $comment->likes + ($liked ? -1 : 1);
        $comment->save();
        return $comment;
    }

    public function report(Request $request, $id)
    {
        //use cache check if report or unreported
        $cache = Cache::get('report_comment_' . $id);
        if (empty($cache)) {
            Cache::put('report_comment_' . $id, 1, 60 * 24);
        }
        $reported = !empty($cache) && $cache;
        if($reported) {
            Cache::put('report_comment_' . $id, 0, 60 * 24);
        }
        $comment          = Comment::find($id);
        $comment->reports = $comment->reports + ($reported ? -1 : 1);
        $comment->save();
        return $comment;
    }
}

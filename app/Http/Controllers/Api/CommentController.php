<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Notifications\ArticleCommented;

class CommentController extends Controller
{
    public function save(Request $request)
    {
        $user             = $request->user();
        $comment          = new Comment($request->all());
        $comment->user_id = $user->id;
        if ($request->get('is_replay_comment')) {
            $comment->lou = 0;
        } else {
            $comment->lou = Comment::where('commentable_id', $request->get('commentable_id'))
                ->where('commentable_type', get_polymorph_types($request->get('commentable_type')))
                ->count() + 1;
        }
        $comment->save();
        if ($request->get('is_replay_comment')) {
            $comment = $comment->commented()->with('user')->with('replyComments')->first();
        }
        $comment->user = $comment->user;

        //notify ..
        if (get_polymorph_types($request->get('commentable_type')) == 'articles') {
            $article = $comment->commentable;
            $article->user->notify(new ArticleCommented($article->id, $user->id, $comment->body, $comment->lou));
            $article->user->forgetUnreads();
        }

        return $comment;
    }

    public function get(Request $request, $id, $type)
    {
        $comments = Comment::with('user')->with('commented.user')->with('replyComments.user')
            ->orderBy('lou')
            ->where('commentable_type', $type)
            ->where('commentable_id', $id)
            ->paginate(5);
        foreach ($comments as $comment) {
            $comment->time     = $comment->createdAt();
            $comment->liked    = $this->check_cache($request, $comment->id, 'like_comment');
            $comment->reported = $this->check_cache($request, $comment->id, 'report_comment');
        }

        return $comments;
    }

    public function like(Request $request, $id)
    {
        $liked          = $this->sync_cache($request, $id, 'like_comment');
        $comment        = Comment::find($id);
        $comment->likes = $comment->likes + ($liked ? -1 : 1);
        $comment->save();
        return $comment;
    }

    public function report(Request $request, $id)
    {
        $reported         = $this->sync_cache($request, $id, 'report_comment');
        $comment          = Comment::find($id);
        $comment->reports = $comment->reports + ($reported ? -1 : 1);
        $comment->save();
        return $comment;
    }

    public function check_cache($request, $id, $type)
    {
        //use cache check if report or unreported
        $cache_key = $type . '_' . $id . '_' . $request->user()->id;
        $cache     = Cache::get($cache_key);
        $done      = !empty($cache) && $cache;
        return $done;
    }

    public function sync_cache($request, $id, $type)
    {
        //use cache check if report or unreported
        $cache_key = $type . '_' . $id . '_' . $request->user()->id;
        $cache     = Cache::get($cache_key);
        if (empty($cache)) {
            Cache::put($cache_key, 1, 60 * 24);
        }
        $done = !empty($cache) && $cache;
        if ($done) {
            Cache::put($cache_key, 0, 60 * 24);
        }
        return $done;
    }
}

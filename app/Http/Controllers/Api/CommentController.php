<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Notifications\ArticleCommented;
use Illuminate\Http\Request;
use App\Action;
use Illuminate\Support\Facades\Cache;

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
        //if article_author commment
        if(request('commentable_type')=='articles_author'){
             $comment->lou = 0;
             $comment->commentable_type=request('commentable_type');
        }

        $comment->save();
        // if ($request->get('is_replay_comment')) {
        //     $comment = $comment->commented()->with('user')->with('replyComments')->first();
        // }
        $comment->user = $comment->user;

        //notify ..
        if (get_polymorph_types($request->get('commentable_type')) == 'articles') {
            $article = $comment->commentable;
            $article->user->notify(new ArticleCommented($article->id, $user->id, $comment->body, $comment->lou));
            $article->user->forgetUnreads();
        }
        //record action
        $action = Action::firstOrNew([
            'user_id'         => $user->id,
            'actionable_type' => 'comments',
            'actionable_id'   => $comment->id,
        ]);
        $action->save();

        //新评论，一起给前端返回 空的子评论 和 子评论的用户信息结构，方便前端直接回复刚发布的新评论
        $comment = Comment::with('user')->with('replyComments.user')->find($comment->id);
        
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
            $comment->liked    = $request->user() ? $this->check_cache($request, $comment->id, 'like_comment') : 0;
            $comment->reported = $request->user() ? $this->check_cache($request, $comment->id, 'report_comment') : 0;
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

<?php

namespace App\Http\Controllers\Api;

use App\Action; 
use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller 
{
    /**
     * @Desc     保存评论
     * @Author   czg
     * @DateTime 2018-07-09
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function save(Request $request)
    {
        $input   = $request->all();
        $comment = new Comment(); 
        return $comment->store($input);
    }

    public function getWithToken(Request $request, $id, $type)
    {
        return $this->get($request, $id, $type);
    }

    public function get(Request $request, $id, $type)
    {
        //一起给前端返回 子评论 和 子评论的用户信息
        $comments = Comment::with('user')->with('commented.user')->with('replyComments.user')
            ->orderBy('lou')
            ->where('comment_id', null)
            ->where('commentable_type', $type)
            ->where('commentable_id', $id)
            ->paginate(5);
        foreach ($comments as $comment) {
            $comment->time     = $comment->createdAt();
            $comment->liked    = $request->user() ? $this->check_cache($request, $comment->id, 'like_comment')   : 0;
            $comment->reported = $request->user() ? $this->check_cache($request, $comment->id, 'report_comment') : 0;
        }

        foreach ($comments as $comment) {
            $comment->replying = 0;
        }

        return $comments;
    }

    public function like(Request $request, $id)
    {
        $user           = $request->user();
        $liked          = $this->sync_cache($request, $id, 'like_comment');
        $comment        = Comment::find($id);
        $comment->likes = $comment->likes + ($liked ? -1 : 1);
        $comment->save();
        $comment->liked = !$liked;

        $like = \App\Like::create([
            'user_id'    => $user->id,
            'liked_id'   => $id,
            'liked_type' => 'comments',
        ]);

        $action = \App\Action::create([
            'user_id'         => $user->id,
            'actionable_type' => 'likes',
            'actionable_id'   => $like->id,
        ]);
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

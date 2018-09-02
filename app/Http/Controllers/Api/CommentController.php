<?php

namespace App\Http\Controllers\Api;

use App\Action; 
use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Notifications\ArticleLiked;
use App\Events\LikeWasCreated;
use App\Events\LikeWasDeleted;
 
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
        $user = $request->user();
        //一起给前端返回 子评论 和 子评论的用户信息
        $comments = Comment::with(['user'=>function($query){
                    $query->select('id','name','avatar');
                }])
            ->with(['commented.user'=>function($query){
                    $query->select('id','name','avatar');
                }])
            ->with(['replyComments.user'=>function($query){
                    $query->select('id','name','avatar');
                }])->with('hasManyLikes') 
            ->orderBy('lou')
            ->where('comment_id', null)
            ->where('commentable_type', $type) 
            ->where('commentable_id', $id)
            ->paginate(5);
        foreach ($comments as $comment) {
            $comment->time     = $comment->createdAt();
            $comment->liked = empty($user)? 0: $comment->hasManyLikes()
                ->where('user_id',$user->id)
                ->exists();
            //TODO 存在BUG-缓存过期状态会消失。目前先不引入report表。
            $comment->reported = empty($user)?0:$this->check_cache($request, $comment->id, 'report_comment');
            $comment->replying = 0; 
        }
        return $comments;
    }
    
    public function like(Request $request, $id)
    {
        $like = new \App\Like();
        $user = $request->user();
        $data = [
            'user_id'   =>  $user->id,
            'liked_id'  =>  $id,
            'liked_type'=>  'comments'
        ];
        return $like->toggleLike($data);
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

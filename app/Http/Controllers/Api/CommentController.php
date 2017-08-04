<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function save(Request $request)
    {
        $comment      = new Comment($request->all());
        $comment->user_id = $request->user()->id;
        $comment->lou = Comment::where('object_id', $request->get('object_id'))->where('type', $request->get('type'))->count() + 1;
        $comment->save();
        return $comment;
    }

    public function get(Request $request, $id, $type)
    {
        $comments = Comment::with('user')->with('comment')->orderBy('lou')->where('type', $type)->where('object_id', $id)->paginate(5);
        return $comments;
    }

    public function like(Request $request, $id)
    {
        //TODO:: 防止用一个用户，对同一个评论重复点赞?  给你们个思路，　可以更新cookie, session ,cache等机制防止....这个api..
        $comment = Comment::find($id);
        $comment->likes ++;
        $comment->save();
        return $comment;
    }

    public function report(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->reports ++;
        $comment->save();
        return $comment;
    }
}

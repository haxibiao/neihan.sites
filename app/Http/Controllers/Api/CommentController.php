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
        $comments = Comment::orderBy('lou')->where('type', $type)->where('object_id', $id)->paginate(10);
        return $comments;
    }
}

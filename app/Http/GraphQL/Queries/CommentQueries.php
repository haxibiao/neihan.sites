<?php

namespace App\Http\GraphQL\Queries;


use App\Comment;

class CommentQueries
{
    public function comments($root, array $args, $context){

        $query = Comment::orderBy('is_accept','desc')
            ->orderBy('id','desc');

        $query->when( isset($args['commentable_id'] ) , function ($q) use ($args){
            return $q->where('commentable_id', $args['commentable_id']);
        });
        $query->when( isset($args['commentable_type'] ) , function ($q) use ($args){
            return $q->where('commentable_type', $args['commentable_type']);
        });
        $query->when( isset($args['user_id'] ) , function ($q) use ($args){
            return $q->where('user_id', $args['user_id']);
        });
       return $query;
    }
}
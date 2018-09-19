<?php

namespace App\GraphQL\Query;

use App\Comment;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class CommentsQuery extends Query
{
    protected $attributes = [
        'name' => 'Comments',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Comment'));
    }

    public function args()
    {
        return [
            'user_id'    => ['name' => 'user_id', 'type' => Type::int()],
            'article_id' => ['name' => 'article_id', 'type' => Type::int()],
            'comment_id' => ['name' => 'comment_id', 'type' => Type::int()],
            'limit'      => ['name' => 'limit', 'type' => Type::int()],
            'offset'     => ['name' => 'offset', 'type' => Type::int()],
            'filter'     => ['name' => 'filter', 'type' => GraphQL::type('CommentFilter')],
            'order'      => ['name' => 'order', 'type' => GraphQL::type('CommentOrder')],
        ];
    }

    public function resolve($root, $args)
    {
        $qb = Comment::where('id', '>=', 0);

        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', $args['user_id']);
        }

        if (isset($args['article_id'])) {
            $qb = $qb->where('commentable_id', $args['article_id'])->where('commentable_type', 'articles')->where('comment_id', null);
        }

        if (isset($args['comment_id'])) {
            $qb = $qb->where('comment_id', $args['comment_id']);
        }

        if (isset($args['filter'])) {
            if ($args['filter'] == 'ONLY_AUTHOR') {
                $article = \App\Article::find($args['article_id']);
                if ($article) {
                    $qb = $qb->where('user_id', $article->user->id);
                }
            }
        }

        if (isset($args['order'])) {
            if ($args['order'] == "LIKED_MOST") {
                $qb = $qb->orderBy('likes', 'desc');
            } elseif ($args['order'] == "OLD_FIRST") {
                $qb = $qb->orderBy('created_at');
            } else {
                $qb = $qb->orderBy('created_at', 'desc');
            }
        }

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);

        $comments = $qb->get();
        foreach ($comments as $comment) {
            //格式化 因评论采用了htmlentities转码 
            $comment->fillForJs();
        }

        return $comments;
    }
}

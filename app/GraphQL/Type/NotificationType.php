<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class NotificationType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Notification',
        'description' => 'A Notification',
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'         => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'Th',
            ],
            'time_ago'   => \App\GraphQL\Field\TimeField::class,
            'read_at'    => [
                'type'        => Type::string(),
                'description' => 'read_at of notification',
                'resolve'     => function ($root, $args) {
                    if ($root->read_at) {
                        return $root->read_at->toDateTimeString();
                    }
                },
            ],
            'time_ago'   => [
                'type'        => Type::string(),
                'description' => 'time ago of notification',
                'resolve'     => function ($root, $args) {
                    return diffForHumansCN($root->created_at);
                },
            ],

            //computed
            'type'       => [
                'type'        => Type::string(),
                'description' => 'type of notification',
                'resolve'     => function ($root, $args) {
                    switch ($root->type) {
                        case "App\\Notifications\\ArticleApproved":
                            return "收录了文章";
                        case "App\\Notifications\\ArticleRejected":
                            return "拒绝了文章";
                        case "App\\Notifications\\ArticleCommented":
                            return "评论了文章";
                        case "App\\Notifications\\ArticleFavorited":
                            return "收藏了文章";
                        case "App\\Notifications\\ArticleLiked":
                            return "喜欢了文章";
                        case "App\\Notifications\\CommentLiked":
                            return "赞了评论";
                        case "App\\Notifications\\ArticleTiped":
                            return "打赏了文章";
                        case "App\\Notifications\\CategoryFollowed":
                            return "关注了专题";
                        case "App\\Notifications\\CategoryRequested":
                            return "投稿了专题";
                        case "App\\Notifications\\CollectionFollowed":
                            return "关注了文集";
                        case "App\\Notifications\\UserFollowed":
                            return "关注了";
                        default:
                            return "其他";
                    }
                },
            ],

            //relation
            'comment'    => [
                'type'        => GraphQL::type('Comment'),
                'description' => 'comment of Notification',
                'resolve'     => function ($root, $args) {
                    if (isset(($root->data['comment_id']))) {
                        $comment = \App\Comment::find($root->data['comment_id']);
                        return $comment;
                    }
                },
            ],
            'article'    => [
                'type'        => GraphQL::type('Article'),
                'description' => 'article of Notification',
                'resolve'     => function ($root, $args) {
                    if (isset(($root->data['article_id']))) {
                        $article = \App\Article::find($root->data['article_id']);
                        return $article;
                    }
                },
            ],
            'question'   => [
                'type'        => GraphQL::type('Question'),
                'description' => 'question of Notification',
                'resolve'     => function ($root, $args) {
                    if (isset(($root->data['question_id']))) {
                        $question = \App\Question::find($root->data['question_id']);
                        return $question;
                    }
                },
            ],
            'user'       => [
                'type'        => GraphQL::type('User'),
                'description' => 'user related to Notification',
                'resolve'     => function ($root, $args) {
                    if (isset(($root->data['user_id']))) {
                        $user = \App\User::find($root->data['user_id']);
                        return $user;
                    }
                },
            ],
            'category'   => [
                'type'        => GraphQL::type('Category'),
                'description' => 'category followed Notification',
                'resolve'     => function ($root, $args) {
                    if (isset(($root->data['category_id']))) {
                        $category = \App\Category::find($root->data['category_id']);
                        return $category;
                    }
                },
            ],
            'collection' => [
                'type'        => GraphQL::type('Collection'),
                'description' => 'collection followed Notification',
                'resolve'     => function ($root, $args) {
                    if (isset($root->data['question_id'])) {
                        $collection = \App\Collection::find($root->data['collection_id']);
                        return $collection;
                    }
                },
            ],
            'tip'        => [
                'type'        => GraphQL::type('Tip'),
                'description' => 'tip Notification',
                'resolve'     => function ($root, $args) {
                    if (isset($root->data['tip_id'])) {
                        return \App\Tip::find($root->data['tip_id']);
                    }
                },
            ],
        ];
    }
}

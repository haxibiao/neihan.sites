<?php
namespace App\GraphQL\Mutation\comment;

use App\Comment;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class addCommentMutation extends Mutation
{

    protected $attributes = [
        'name'        => 'addCommentMutation',
        'description' => 'create a Comment ',
    ];

    public function type() 
    {
        return GraphQL::type('Comment');
    }

    public function args()
    {
        return [
            'commentable_id' => ['name' => 'commentable_id', 'type' => Type::int()],
            'body'           => ['name' => 'body', 'type' => Type::string()],
            'comment_id'     => ['name' => 'comment_id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'commentable_id' => ['required'],
            'body'           => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $comment = new Comment();
        $args['commentable_type'] = 'articles';

        $comment = $comment->store($args);

        //记录到哈希表 非线上环境不记录
        if(!\App::environment('local')){
            $user_id = checkUser() ? getUser()->id : null;
            $behavior = 'addComment';
            $behavior_id = $comment->id;
            $behavior_title = mb_substr($comment->body, 0, 30, 'utf-8');    //截取30个utf8字符
            \App\Helpers\HxbUtils::recordTaffic($user_id, $behavior,$behavior_id,$behavior_title);
        }

        return $comment;
    }
}

<?php
namespace App\GraphQL\Mutation\like;

use App\Article;
use App\Like;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class likeArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'likeArticleMutation',
        'description' => 'like a Article ',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }
 
    public function args()
    {
        return [
            'article_id' => ['name' => 'article_id', 'type' => Type::int()],
            'undo'       => [
                'name' => 'undo', 
                'type' => Type::boolean(),
                'defaultValue' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            'article_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $data = [
            'liked_id'   => $args['article_id'],
            'liked_type' => 'articles',
            'undo'       => $args['undo']
        ];
        $like = new Like();
        return $like->toggleLike($data);
    }
}

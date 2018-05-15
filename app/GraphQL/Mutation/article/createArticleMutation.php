<?php
namespace App\GraphQL\Mutation\article;

use App\Article;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class createArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'createArticleMutation',
        'description' => '创建文章  ',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'title'         => ['name' => 'title', 'type' => Type::string()],
            'body'          => ['name' => 'body', 'type' => Type::string()],
            'collection_id' => ['name' => 'title', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'title' => ['required'],
            'body'  => ['required|min:20|not_copyed_image'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $article = Article::create([
            'user_id' => $user->id,
            'title' => $args['title'],
            'body'  => $args['body'],
            'collection_id' => $args['collection_id']
        ]);

        return $article;
    }
}

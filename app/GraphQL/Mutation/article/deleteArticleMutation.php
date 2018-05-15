<?php
namespace App\GraphQL\Mutation\article;

use App\Article;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class deleteArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'deleteArticleMutation',
        'description' => ' 彻底删除文章',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'id'   => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'id'   => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $article       = Article::findOrFail($args['id']);
        $article->delete();

        return $article;
    }
}

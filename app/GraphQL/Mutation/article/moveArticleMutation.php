<?php
namespace App\GraphQL\Mutation\article;

use App\Article;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class moveArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'moveArticleMutation',
        'description' => '移动文章',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'article_id'         => ['name' => 'article_id', 'type' => Type::int()],            
            'collection_id' => ['name' => 'collection_id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'article_id' => ['required'],
            'collection_id'  => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $article = Article::findOrFail($args['article_id']);
        $article->collections()->sync($args['collection_id']);
        return $article;
    }
}

<?php
namespace App\GraphQL\Mutation\article;

use App\Article;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class reportArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'reportArticleMutation',
        'description' => ' 举报文章',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'id'     => ['name' => 'id', 'type' => Type::int()],
            'type'   => ['name' => 'type', 'type' => Type::string()],
            'reason' => ['name' => 'reason', 'type' => Type::string()],
        ];
    }

    public function rules()
    {
        return [
            'id'   => ['required'],
            'type' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $article = Article::findOrFail($args['id']);
        $reason  = isset($args['reason']) ? $args['reason'] : '';
        $article->report($args['type'], $reason);
        return $article;
    }
}

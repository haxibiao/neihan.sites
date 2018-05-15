<?php
namespace App\GraphQL\Mutation\article;

use App\Article;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class editArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'editArticleMutation',
        'description' => '修改文章',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'id'            => ['name' => 'id', 'type' => Type::int()],
            'title'         => ['name' => 'title', 'type' => Type::string()],
            'body'          => ['name' => 'body', 'type' => Type::string()],
            'collection_id' => ['name' => 'title', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'id'    => ['required'],
            'title' => ['required'],
            'body'  => ['required|min:20|not_copyed_image'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $article = Article::findOrFail($args['id']);
        $article->update([
            'title'         => $args['title'],
            'body'          => $args['body'],
            'collection_id' => $args['collection_id'],
        ]);

        return $article;
    }
}

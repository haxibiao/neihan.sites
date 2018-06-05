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
            'collection_id' => ['name' => 'collection_id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'title' => ['required'],
            'body'  => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $article = Article::create([
            'user_id' => $user->id,
            'title'   => $args['title'],
            'body'    => $args['body'],
        ]);

        if (isset($args['collection_id'])) {
            $article->collections()->sync($args['collection_id']);
        } else {
            $userDefaultCollection = $user->collections()->orderBy('id')->first();
            if ($userDefaultCollection) {
                $article->collections()->sync($userDefaultCollection->id);
            }
        }

        return $article;
    }
}

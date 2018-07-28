<?php
namespace App\GraphQL\Mutation\post;

use App\Article;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class createPostMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'createPostMutation',
        'description' => '创建动态',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'body'         => ['name' => 'body', 'type' => Type::string()],
            'video_id'     => ['name' => 'video_id', 'type' => Type::int()],
            'image_urls'   => ['name' => 'image_urls', 'type' => Type::listOf(Type::string())],
            'category_ids' => ['name' => 'category_ids', 'type' => Type::listOf(Type::int())],
        ];
    }

    public function rules()
    {
        return [
            'body' => ['required'],
        ];
    }
    /**
     * @Desc
     * @DateTime 2018-07-15
     * @param    [type]     $root [description]
     * @param    [type]     $args [description]
     * @return   [type]           [description]
     */
    public function resolve($root, $args)
    {
        $article = new Article();
        $article->createPost($args);

        //直接关联到专题
        if (!empty($args['category_ids'])) {
            $article->categories()->sync($args['category_ids']);
        }

        return $article;
    }
}

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
            'body'           => ['name' => 'body', 'type'       => Type::string()],
            'video_id'       => ['name' => 'video_id', 'type'   => Type::int()],
            'image_urls'     => ['name' => 'image_urls', 'type' => Type::listOf(Type::string())],
            'a_cids'         => ['name' => 'a_cids', 'type'     => Type::listOf(Type::int())],

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
        return $article->createPost( $args );
    }
}

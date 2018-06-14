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
            'collection_id' => ['name' => 'collection_id', 'type' => Type::int()],
            'is_publish'    => ['name' => 'is_publish', 'type' => Type::boolean()],
        ];
    }

    public function rules()
    {
        return [
            'id'    => ['required'],
            'title' => ['required'],
            /**
             * TODO
             * 'body'  => ['required|min:20|not_copyed_image']之前的验证规则，
             * 但是graphql好像不全部兼容laravel自带验证规则，暂时放在这．
             */
            'body'  => ['required'],
        ];
    }
     
    public function resolve($root, $args)
    {
        $user = getUser();
        $article = Article::findOrFail($args['id']);
        $update_paramters = [
            'title'         => $args['title'],
            'body'          => $args['body'] ,
        ];
        if( isset( $args['collection_id'] ) ){ 
            $update_paramters['collection_id'] = $args['collection_id'];
        }
        if( isset( $args['is_publish'] ) && $args['is_publish'] ){
            $update_paramters['status'] = 1;//１代表发布状态
        }
        $article->update( $update_paramters);

        return $article; 
    }
}

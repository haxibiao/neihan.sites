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
            'a_cids' => ['name' => 'a_cids', 'type' => Type::listOf(Type::int())],
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
        if (!empty($args['a_cids'])) {
            //排除重复专题
            $category_ids = array_unique($args['a_cids']);
            $category_id = reset($category_ids);
            array_shift($category_ids);

            //第一个专题为主专题
            $article->category_id = $category_id;
            $article->save();

            if($category_ids){
                $article->categories()->sync($category_ids);
            }
        }

        //记录到哈希表 非线上环境不记录
        if(!\App::environment('local')){
            $user_id = checkUser() ? getUser()->id : null;
            $behavior = $article->type == 'video' ? 'createVideo' : 'createPost';
            $behavior_id = $article->type != 'video' ? $article->id : $article->video_id;
            $behavior_title = $article->title ?: $article->get_description();
            \App\Helpers\HxbUtils::recordTaffic($user_id, $behavior,$behavior_id,$behavior_title);
        }

        return $article;
    }
}

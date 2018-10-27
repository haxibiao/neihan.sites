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
            'is_publish'    => ['name' => 'is_publish', 'type' => Type::boolean()],
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
        //TODO　　避免用户误操作 重复请求 创建相同的文章
        $article          = new Article();
        $article->user_id = $user->id;
        $article->title   = $args['title'];
        $article->body    = $args['body'];
        $article->status  = 0; //默认私密 这里0代表文章是私密状态１代表文章是发布状态
        if (isset($args['is_publish']) && $args['is_publish']) {
            $article->status = 1;
        }
        $article->count_words = ceil(strlen(strip_tags($article->body)) / 2);
        $article->save();
        //统计article相关信息
        if($article->status == 1){
            //统计用户的文章数，字数
            $user->count_articles = $user->articles()->count();
            $user->count_words    = $user->articles()->sum('count_words');
            $article->recordAction();
            $user->save();
        }



        if (isset($args['collection_id'])) {
            $article->collection_id = $args['collection_id'];
        } else {
            $userDefaultCollection = $user->collections()->orderBy('id')->first();
            if ($userDefaultCollection) {
                $article->collection_id = $userDefaultCollection->id;
            }
        }
        $article->save();

        //记录到哈希表 非线上环境不记录
        if(!\App::environment('local')){
            $user_id = checkUser() ? getUser()->id : null;
            $behavior = 'createArticle';
            $behavior_id = $article->type != 'video' ? $article->id : $article->video_id;
            $behavior_title = $article->title ?: $article->get_description();
            \App\Helpers\HxbUtils::recordTaffic($user_id, $behavior,$behavior_id,$behavior_title);
        }

        return $article;
    }
}

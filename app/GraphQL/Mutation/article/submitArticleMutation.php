<?php
namespace App\GraphQL\Mutation\article;

use App\Article;
use App\Category;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class submitArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'submitArticleMutation',
        'description' => '投稿/收录 一篇文章到一个专题',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'article_id'  => ['name' => 'article_id', 'type' => Type::int()],
            'category_id' => ['name' => 'category_id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'article_id'  => ['required'],
            'category_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user            = getUser();
        $article         = Article::findOrFail($args['article_id']);
        $category        = Category::findOrFail($args['category_id']);
        $submited_status = '';
        $query           = $article->allCategories()->wherePivot('category_id', $category->id);
        if ($query->count()) {
            foreach ($query->get() as $cate) {
                $pivot           = $cate->pivot;
                $submited_status = $category->isAdmin($user) ? ($pivot->submit == '已收录' ? '已移除' : '已收录') : ($pivot->submit == '待审核' ? '已撤回' : '待审核');
                $pivot->submit   = $submited_status;
                $pivot->save();
            }
        } else {
            $submited_status = $category->isAdmin($user) ? '已收录' : '待审核';
            //文章和分类的关系就在这，不能重复投稿，不然关联取通过审核的文章或者分类会重复！！
            $article->allCategories()->sync([
                $category->id => [
                    'submit' => $submited_status,
                ],
            ]);
        }

        //给所有管理员延时1分钟发通知，提示有新的投稿请求
        //下面代码注释的原因是现在投稿通知的逻辑不通过消息通知表的流程，而是在代码层面计算
        /*if ($submited_status == '待审核') {
            \App\Jobs\SendCategoryRequest::dispatch($article, $category)->delay(now()->addSeconds(1));
        }*/

        $article->submited_status = $submited_status;
        $article->submit_status   = get_submit_status($submited_status);

        return $article;
    }
}

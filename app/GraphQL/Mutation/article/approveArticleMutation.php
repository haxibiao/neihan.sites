<?php
namespace App\GraphQL\Mutation\article;

use App\Article;
use App\Category;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class approveArticleMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'approveArticleMutation',
        'description' => 'approve一篇文章到一个专题',
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
            'is_reject'   => ['name' => 'is_reject', 'type' => Type::boolean()],
        ];
    }

    public function rules()
    {
        return [
            'article_id'  => ['required'],
            'category_id' => ['required'],
            'is_reject'   => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $aid       = $args['article_id'];
        $cid       = $args['category_id'];
        $is_reject = $args['is_reject'];

        $user = getUser();
        //文章or专题有可能被删除了，先把未读消息处理了
        foreach ($user->notifications as $notification) {
            $data = $notification->data;
            if ($data['type'] == 'category_request') {
                if ($data['article_id'] == $aid && $data['category_id'] == $cid) {
                    $notification->markAsRead();
                }
            }
        }

        //总是记得清缓存，无论 接受 or 拒绝，后面成功ｏｒ失败 ...
        $user->forgetUnreads();

        $category = Category::findOrFail($cid);
        $article  = Article::findOrFail($aid);

        //更新投稿请求的状态
        $article = $category->newRequestArticles()->wherePivot('article_id', $article->id)->first();
        if (!$article) {
            throw new \Exception('文章没在最新投稿列表了');
        }
        // $submited_status = '待审核';
        $pivot         = $article->pivot;
        $pivot->submit = $is_reject ? '已拒绝' : '已收录';
        $pivot->save();
        $submited_status = $pivot->submit;
        if ($pivot->submit == '已收录') {
            //接受文章，更新专题文章数
            $category->count = $category->publishedArticles()->count();
            $category->save();
        }

        //重新统计专题上的未处理投稿数...
        $category->new_requests = $category->newRequestArticles()->count();
        $category->save();

        //发送通知给投稿者
        $article->user->notify(new \App\Notifications\ArticleApproved($article, $category, $submited_status));
        $article->user->forgetUnreads();

        //更新文章主分类,方便上首页
        $article->category_id = $cid;
        $article->save();

        //收录状态返回给UI
        $article->submit_status   = get_submit_status($submited_status);
        $article->submited_status = $submited_status;

        return $article;
    }
}

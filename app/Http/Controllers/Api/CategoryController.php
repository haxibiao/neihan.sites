<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Http\Controllers\Controller;
use App\Jobs\SendCategoryRequest;
use App\Notifications\ArticleApproved;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    public function newLogo(Request $request)
    {
        $category = new Category();
        $category->saveLogo($request);
        return $category->logo();
    }
    public function editLogo(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->saveLogo($request);
        $category->save();
        return $category->logo();
    }

    public function index(Request $request)
    {
        $categories = Category::orderBy('updated_at', 'desc')->get();
        foreach ($categories as $category) {
            $category->fillForJs();
        }
        return $categories;
    }

    public function page(Request $request)
    {
        $categories = Category::orderBy('updated_at', 'desc')->paginate(7);
        foreach ($categories as $category) {
            $category->fillForJs();
        }
        return $categories;
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        $category->fillForJs();
        return $category;
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        $category->fillForJs();
        return $category;
    }

    public function checkCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $user     = $request->user();
        $isAdmin  = $category->admins->contains($user);
        $query    = $user->articles();
        if (request('q')) {
            $query = $query->where('title', 'like', '%' . request('q') . '%');
        }
        $articles = $query->paginate(10);
        foreach ($articles as $article) {
            $article->submited_status = '';
            $query                    = $article->allCategories()->wherePivot('category_id', $category->id);
            if ($query->count()) {
                $article->submited_status = $query->first()->pivot->submit;
            }
            //如果是专题的管理
            if ($isAdmin) {
                $article->submit_status = $article->submited_status == '已收录' ? '移除' : '收录';
            } else {
                $article->submit_status = get_submit_status($article->submited_status);
            }
        }

        return $articles;
    }

    public function submitCategory(Request $request, $aid, $cid)
    {
        $user     = $request->user();
        $article  = Article::findOrFail($aid);
        $category = Category::findOrFail($cid);

        $query = $article->allCategories()->wherePivot('category_id', $cid);
        if ($query->count()) {
            $pivot         = $query->first()->pivot;
            $pivot->submit = $pivot->submit == '待审核' ? '已撤回' : '待审核';
            $pivot->save();
            $article->submited_status = $pivot->submit;
        } else {
            $article->submited_status = '待审核';

            //文章和分类的关系就在这，不能重复投稿，不然关联取通过审核的文章或者分类会重复！！
            $article->allCategories()->sync([
                $cid => [
                    'submit' => $article->submited_status,
                ],
            ]);
        }

        //给所有管理员延时10分钟发通知，提示有新的投稿请求
        if ($article->submited_status == '待审核') {
            SendCategoryRequest::dispatch($article, $category)->delay(now()->addMinutes(1));
        }

        $article->submit_status = get_submit_status($article->submited_status);
        return $article;
    }

    public function addCategory(Request $request, $aid, $cid)
    {
        $user     = $request->user();
        $article  = Article::findOrFail($aid);
        $category = Category::findOrFail($cid);

        $query = $category->articles()->wherePivot('article_id', $aid);
        if ($query->count()) {
            $pivot         = $query->first()->pivot;
            $pivot->submit = $pivot->submit == '已收录' ? '已移除' : '已收录';
            $pivot->save();
            $submited_status = $pivot->submit;
        } else {
            $category->articles()->syncWithoutDetaching([
                $aid => [
                    'submit' => '已收录',
                ],
            ]);
            $submited_status = '已收录';
        }

        if ($submited_status == '已收录') {
            if ($user->id != $article->user->id) {
                //通知文章作者文章被收录
                $article->user->notify(new ArticleApproved($article, $category, $submited_status));
                $article->user->forgetUnreads();
            }
            //被收录文章的主专题标签更新
            $article->category_id = $cid;
            $article->save();
        } else {
            //移除收录的文章，不再通知，但是应该更新文章的主专题为最后一次的主专题
            $lastCategory = $article->allCategories()->orderBy('pivot_updated_at', 'desc')->first();
            if ($lastCategory) {
                //被收录文章的主专题标签更新
                $article->category_id = $lastCategory->id;
                $article->save();
            }
        }

        //更新专题文章数
        $category->count = $category->publishedArticles()->count();
        $category->save();

        //返回给ui
        $category->submit_status   = $submited_status == '已收录' ? '移除' : '收录';
        $category->submited_status = $submited_status;
        return $category;
    }

    public function adminCategoriesCheckArticle(Request $request, $aid)
    {
        $user = $request->user();
        $qb   = $user->adminCategories()->with('user');
        if (request('q')) {
            $qb = $qb->where('categories.name', 'like', request('q') . '%');
        }
        $categories = $qb->paginate(12);
        //获取当前文章的投稿状态
        foreach ($categories as $category) {
            $category->submited_status = '';
            $query                     = $category->articles()->wherePivot('article_id', $aid);
            if ($query->count()) {
                $category->submited_status = $query->first()->pivot->submit;
            }
            $category->submit_status = $category->submited_status == '已收录' ? '移除' : '收录';
            $category->fillForJs();
        }
        return $categories;
    }

    public function recommendCategoriesCheckArticle(Request $request, $aid)
    {
        $categories = Category::orderBy('id', 'desc')->paginate(9);
        //获取当前文章的投稿状态
        $categoriesWithOutMine = [];
        foreach ($categories as $category) {
            $category->fillForJs();
            $category->submited_status = '';
            $query                     = $category->articles()->wherePivot('article_id', $aid);
            if ($query->count()) {
                $category->submited_status = $query->first()->pivot->submit;
            }
            $category->submit_status = get_submit_status($category->submited_status);
            if (!$category->isOfUser($request->user())) {
                $categoriesWithOutMine[] = $category;
            }
        }
        //推荐专题不包含可以收录的
        $categoriesNotMine　 = new LengthAwarePaginator(new \Illuminate\Support\Collection($categoriesWithOutMine), $categories->total(), 9);
        return $categoriesNotMine　;
    }

    

    public function approveCategory(Request $request, $aid, $cid)
    {
        $user = $request->user();

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
        $query           = $article->allCategories()->wherePivot('category_id', $cid);
        $submited_status = '待审核';
        if ($query->count()) {
            //TODO::这里如果重复投稿，就出问题了
            // $pivot = $query->first()->pivot;
            foreach ($query->get() as $cate) {
                $pivot = $cate->pivot;
                if ($request->get('remove')) {
                    $pivot->delete();
                } else {
                    $pivot->submit = $request->get('deny') ? '已拒绝' : '已收录';
                    $pivot->save();
                    $submited_status = $pivot->submit;

                    if ($pivot->submit == '已收录') {
                        //接受文章，更新专题文章数
                        $category->count = $category->publishedArticles()->count();
                        $category->save();
                    }
                }
            }
        }

        // return $category->articles()->wherePivot('submit', '待审核')->get();

        //重新统计专题上的未处理投稿数...
        $category->new_requests = $category->articles()->wherePivot('submit', '待审核')->count();
        $category->save();

        //发送通知给投稿者
        $article->user->notify(new ArticleApproved($article, $category, $submited_status));
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

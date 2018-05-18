<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Http\Controllers\Controller;
use App\Notifications\ArticleApproved;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function search(Request $request, $aid)
    {
        $article    = Article::findOrFail($aid);
        $query      = $request->get('q');
        $categories = Category::where('name', 'like', '%' . $query . '%')
            ->paginate(12);
        foreach ($categories as $category) {
            $cate                      = $article->categories()->where('categories.id', $category->id)->first();
            $category->submited_status = "";
            if ($cate) {
                $category->submited_status = $cate->pivot->submit;
            }
            $category->fillForJs();
            $category->submit_status = get_submit_status($category->submited_status);
        }
        return $categories;
    }

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

    //投稿请求的逻辑放这里了
    public function newReuqestCategories(Request $request)
    {
        $user = $request->user();
        return $user->newReuqestCategories;
    }

    public function pendingArticles(Request $request)
    {
        $user     = $request->user();
        $articles = [];
        foreach ($user->adminCategories as $category) {
            foreach ($category->newRequestArticles->load('user') as $article) {
                $articles[] = $article;
            }
        }
        return $articles;
    }

    public function requestedArticles(Request $request, $cid)
    {
        $category = Category::findOrFail($cid);
        return $category->requestedInMonthArticles->load('user');
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
            $article->allCategories()->syncWithoutDetaching([
                $cid => [
                    'submit' => $article->submited_status,
                ],
            ]);
        }

        //给所有管理员延时10分钟发通知，提示有新的投稿请求
        if ($article->submited_status == '待审核') {
            // SendCategoryRequest::dispatch($article, $category)->delay(now()->addMinutes(1));

            //给所有专题管理发通知
            foreach ($category->admins as $admin) {
                $admin->forgetUnreads();
            }
            //also send to creator
            $category->user->forgetUnreads();

            //TODO::如果后面撤回了，这个标题也留这了
            $category->new_request_title = $article->title;
            //更新单个专题上的新请求数
            $category->new_requests = $category->articles()->wherePivot('submit', '待审核')->count();
            $category->save();
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
        $article = Article::findOrFail($aid);
        $user    = $request->user();
        $qb      = $user->adminCategories()->with('user');
        if (request('q')) {
            $qb = $qb->where('categories.name', 'like', request('q') . '%');
        }
        $categories = $qb->paginate(12);
        //获取当前文章的投稿状态
        foreach ($categories as $category) {
            $category->submited_status = '';
            $cateWithPivot             = $article->categories()->wherePivot('category_id', $category->id)->first();
            if ($cateWithPivot) {
                $category->submited_status = $cateWithPivot->pivot->submit;
            }
            $category->submit_status = $category->submited_status == '已收录' ? '移除' : '收录';
            $category->fillForJs();
        }
        return $categories;
    }

    public function recommendCategoriesCheckArticle(Request $request, $aid)
    {
        $article    = Article::findOrFail($aid);
        $user       = $request->user();
        $categories = Category::orderBy('id', 'desc')->whereNotIn('id', $user->adminCategories()->pluck('categories.id'))->paginate(12);
        $categories->map(function ($category) use ($article) {
            $category->submited_status = '';
            $cateWithPivot             = $article->categories()->wherePivot('category_id', $category->id)->first();
            if ($cateWithPivot) {
                $category->submited_status = $cateWithPivot->pivot->submit;
            }
            $category->submit_status = $category->submited_status == '已收录' ? '移除' : '投稿';
            $category->fillForJs();
            return $category;
        });
        return $categories;
    }

    public function approveCategory(Request $request, $cid, $aid)
    {
        $user = $request->user();
        $user->forgetUnreads();

        $category = Category::findOrFail($cid);
        $article  = $category->requestedInMonthArticles()->where('article_id', $aid)->firstOrFail();

        //更新投稿请求的状态
        $pivot         = $article->pivot;
        $pivot->submit = $request->get('deny') ? '已拒绝' : '已收录';
        if ($request->get('remove')) {
            $pivot->submit = '已移除';
        }
        $pivot->save();
        if ($pivot->submit == '已收录') {
            //接受文章，更新专题文章数
            $category->count = $category->publishedArticles()->count();
            //更新文章主分类,方便上首页
            $article->category_id = $cid;
            $article->save();
        }

        //重新统计专题上的未处理投稿数...
        $category->new_requests = $category->articles()->wherePivot('submit', '待审核')->count();
        $category->save();

        //发送通知给投稿者
        $article->user->notify(new ArticleApproved($article, $category, $pivot->submit));
        $article->user->forgetUnreads();

        //收录状态返回给UI
        // $article->submit_status   = get_submit_status($submited_status);
        // $article->submited_status = $submited_status;
        $article->load('user');

        //自动置顶最新收录的文章到发现，时间７天
        stick_article([
            'article_id' => $article->id,
            'expire'     => 7,
            'position'   => '发现',
            'reason'     => '新收录',
        ], true);

        return $article;
    }
}

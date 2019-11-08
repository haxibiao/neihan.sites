<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Comment;
use App\Favorite;
use App\Http\Controllers\Controller;
use App\Image;
use App\Like;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    //首页文章列表的api
    public function index()
    {
        $articles = indexArticles();
        //下面是为了兼容VUE
        foreach ($articles as $article) {
            $article->fillForJs();
            $article->time_ago = $article->updatedAt();
        }
        return $articles;
    }

    public function fakeUsers()
    {
        return User::where('role_id', 1)->get();
    }

    //TODO:: 爬虫往本站导入文章的接口，还需要慢慢review ...
    public function import(Request $request)
    {
        $data     = $request->get('data');
        $jsonData = json_decode($data, true);
        $user_id  = $jsonData['user_id'];

        $category = $jsonData['category'];

        //category
        $default_import_category_name = config('seo.' . get_domain_key() . '.default_import_category_name');
        if (empty($default_import_category_name)) {
            $category = Category::firstOrnew([
                'name'    => $category['name'],
                'name_en' => $category['name_en'],
            ]);
        } else {
            $category = Category::where('name', $default_import_category_name)->first();
        }

        // if (!$category->id)
        {
            $category->user_id = $user_id;
            $category->status  = 1;
            $category->type    = 'article';
            $category->save();

            //category admin
            $category->admins()->syncWithoutDetaching([$user_id => ['is_admin' => 1]]);
        }

        //article
        $article = Article::firstOrnew([
            'source_url' => $jsonData['article']['source_url'],
        ]);
        $article->fill($jsonData['article']);
        $article->user_id     = $user_id;
        $article->category_id = $category->id;
        $article->status      = 1;
        $article->count_words = count_words($article->body);
        $article->body        = fix_article_body_images($article->body);

        //random time
        $article->updated_at = strtotime($jsonData['time']);
        $article->created_at = strtotime($jsonData['time']);
        $article->save(['timestamp' => false]);

        //images
        if (!empty($jsonData['article']['images'])) {
            foreach ($jsonData['article']['images'] as $image) {
                $image = Image::firstOrnew([
                    'path' => $image['path'],
                ]);
                $image->source_url = 'https://haxibiao.com/' . $image['path'];
                $image->title      = $article->title;
                $image->save();
                $img_ids[] = $image->id;
            }
            $article->images()->sync($img_ids);
        }
        $article->load('images');

        $article->categories()->syncwithoutDetaching([
            $category->id => [
                'submit' => '已收录',
            ],
        ]);
        $article->save(['timestamps' => false]);

        //category
        $category->count = $category->publishedArticles()->count();
        $category->save();

        return $article;
    }

    public function trash(Request $request)
    {
        $user     = $request->user();
        $articles = $user->removedArticles;
        return $articles;
    }

    public function store(Request $request)
    {
        $article          = new Article($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        //images
        $article->saveRelatedImagesFromBody();

        return $article;
    }

    public function update(Request $request, $id)
    {
        $user                 = $request->user();
        $article              = Article::findOrFail($id);
        $article->count_words = ceil(strlen(strip_tags($article->body)) / 2);
        $article->update($request->all());

        //images
        $article->saveRelatedImagesFromBody();

        return $article;
    }

    public function destroy(Request $request, $id)
    {
        \DB::beginTransaction();
        try
        {
            //彻底删除文章，删除相关数据
            Comment::where('comment_id', '=', $id)->where('commentable_type', '=', 'articles')->delete();
            Like::where('liked_id', '=', $id)->where('liked_type', '=', 'articles')->delete();
            Favorite::where('faved_id', '=', $id)->where('faved_type', '=', 'articles')->delete();

            $result = Article::destroy($id);
            \DB::commit();
            return $result;
        } catch (\Exception $e) {
            \DB::rollBack();
            return 0;
        }
    }

    public function delete(Request $request, $id)
    {
        $article         = Article::findOrFail($id);
        $article->status = -1;
        $article->save();
        return $article;
    }

    public function restore(Request $request, $id)
    {
        $article         = Article::findOrFail($id);
        $article->status = 0;
        $article->save();
        //如果文集也被删除了，恢复出来
        if ($article->collection->status == -1) {
            $article->collection->status = 0;
            $article->collection->save();
        }

        return $article;
    }

    public function likes(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $likes   = $article->likes()->with('user')->paginate(10);
        foreach ($likes as $like) {
            $like->created_at = $like->createdAt();
        }
        return $likes;
    }

    public function show($id)
    {
        $article = Article::with('user')->with('category')->with('images')->findOrFail($id);
        $article->fillForJs();
        if (!empty($article->category_id)) {
            $article->category->fillForJs();
        }
        $article->pubtime = diffForHumansCN($article->created_at);
        return $article;
    }

    public function resolverDouyinVideo(Request $request)
    {
        if (isset($request->all()['params']['share_link'])) {
            $data = $request->all()['params'];
        } else {
            return response('请检查输入的信息是否正确完整噢');
        }

        // 效验登录
        $user_id = $data['user_id'];
        if (empty($user_id) || is_null($user_id)) {
            return response('当前未登录，请登录后再尝试哦');
        }

        // 登录
        Auth::loginUsingId($user_id);

        // 过滤文本，留下 url
        $link = $data['share_link'];
        $link = filterText($link)[0];

        // 不允许重复视频
        if (Article::where('source_url', $link)->exists()) {
            return response('视频已经存在，请换一个视频噢');
        }

        // 爬取关键信息
        $spider = app('DouyinSpider');
        $data   = json_decode($spider->parse($link), true);

        // 去除 “抖音” 关键字, TODO :做一个大些的关键词库，封装重复操作
        $data['0']['desc'] = str_replace('@抖音小助手', '', $data['0']['desc']);
        $data['0']['desc'] = str_replace('抖音', '', $data['0']['desc']);

        // 保存并 更新原链接
        $article = new Article();
        $article = $article->parseDouyinLink($data);
        $article->update([
            'source_url' => $link,
        ]);

        return $article;
    }
}

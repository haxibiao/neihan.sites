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
use Illuminate\Http\Request;

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
        return User::where('is_editor', 1)->get();
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

        //user
        $user                 = User::findOrFail($user_id);
        $user->count_articles = $user->articles()->count();
        $user->count_words    = $user->articles()->sum('count_words');
        $user->save();

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

        if ($request->status == 1) {
            //可能是发布了文章，需要统计文集的文章数，字数
            $collections = $article->collection->get();
            foreach ($collections as $collection) {
                $collection->count       = $collection->articles()->count();
                $collection->count_words = $collection->articles()->sum('count_words');
                $collection->save();
            }

            //统计用户的文章数，字数
            $user->count_articles = $user->articles()->count();
            $user->count_words    = $user->articles()->sum('count_words');
            $article->recordAction();
            $user->save();
        }

        return $article;
    }

    public function destroy(Request $request, $id)
    {
        \DB::beginTransaction();
        try
        {
            $comments = Comment::where('comment_id', '=', $id)->where('commentable_type', '=', 'articles')->delete();
            $likes    = Like::where('liked_id', '=', $id)->where('liked_type', '=', 'articles')->delete();
            $favorite = Favorite::where('faved_id', '=', $id)->where('faved_type', '=', 'articles')->delete();
            $user     = \Auth::User();
            $user->decrement('count_articles');
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
}

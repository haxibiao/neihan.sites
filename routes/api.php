<?php

use App\Article;
use App\Category;
use App\Favorite;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\VideoController;
use App\Image;
use App\Traffic;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//提交收藏
Route::middleware('auth:api')->post('/favorite/{id}/{type}', function (Request $request, $id, $type) {
    $favorite = Favorite::firstOrNew([
        'user_id'   => $request->user()->id,
        'object_id' => $id,
        'type'      => $type,
    ]);
    $favorite->save();
    return $favorite->id;
});

//删除收藏
Route::middleware('auth:api')->delete('/favorite/{id}/{type}', function (Request $request, $id, $type) {
    $favorite = Favorite::firstOrNew([
        'user_id'   => $request->user()->id,
        'object_id' => $id,
        'type'      => $type,
    ]);
    $favorite->delete();
    return 1;
});

//查询是否已收藏
Route::middleware('auth:api')->get('/favorite/{id}/{type}', function (Request $request, $id, $type) {
    $favorite = Favorite::firstOrNew([
        'user_id'   => $request->user()->id,
        'object_id' => $id,
        'type'      => $type,
    ]);
    return $favorite->id;
});

Route::get('/articles', function (Request $request) {
    $query = Article::orderBy('id', 'desc');
    if ($request->get('query')) {
        $query = $query->where('title', 'like', '%' . $request->get("query") . '%');
    }
    return $query->paginate(12);
});

Route::get('/keywords', function () {
    $categories = Category::orderBy('id', 'desc')->pluck('name');
    return $categories;
});

//获取用户详细资料
Route::get('/user/{id}', function (Request $request, $id) {
    $user         = User::findOrFail($id);
    $data['user'] = $user;

    $data['articles_count'] = Article::where('user_id', $user->id)->count();
    $data['traffic_count']  = Traffic::where('user_id', $user->id)->count();

    $data['articles_count_yesterday'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();
    $data['traffic_count_yesterday']  = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();

    $data['articles_count_today'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();
    $data['traffic_count_today']  = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();

    return $data;
});

//get user id by user name
Route::get('/user/by-name/{name}', function (Request $request, $name) {
    $user = User::where('name', $name)->first();
    return $user;
});

//获取用户上传的图片，可以按标题搜索
Route::get('/user/{id}/images', function (Request $request, $id) {
    $query = Image::where('user_id', $id)->where('count', '>', 0)->orderBy('updated_at', 'desc');
    if ($request->get('title')) {
        $query = $query->where('title', 'like', '%' . $request->get('title') . '%');
    }
    $images = $query->paginate(12);
    foreach ($images as $image) {
        $image->path       = get_img($image->path);
        $image->path_small = get_img($image->path_small);
    }
    return $images;
});

//获取用户上传的视频，可以按标题搜索
Route::get('/user/{id}/videos', function (Request $request, $id) {
    $query = Video::where('user_id', $id)->where('count', '>=', 0)->orderBy('updated_at', 'desc');
    if ($request->get('title')) {
        $query = $query->where('title', 'like', '%' . $request->get('title') . '%');
    }
    $videos = $query->paginate(12);
    foreach ($videos as $video) {
        $video->path  = get_img($video->path);
        $video->cover = get_img($video->cover);
    }
    return $videos;
});

//保存文章相关片段数据
Route::post('/article/{id}/json', function (Request $request, $id) {
    $article = Article::findOrFail($id);
    $data    = json_decode($article->json, true);
    if (empty($data)) {
        $data = [];
    }
    $data[]        = $request->all();
    $article->json = json_encode($data);
    $article->save();

    //同时更新被关联文章的默认关联集合
    foreach ($request->get('aids') as $aid) {
        $article_connected = Article::find($aid);
        $json_data         = json_decode($article_connected->json, true);
        if (empty($json_data)) {
            $json_data = [];
        }
        $exist_item = null;
        $exist_key  = 0;
        foreach ($json_data as $key => $item) {
            if (!empty($item['title']) && $item['title'] == "本文正被其他文章引用") {
                $exist_item = $item;
                $exist_key  = $key;
            }
        }
        if (!$exist_item) {
            $connect_item = [
                'col'   => 'col-md-6',
                'title' => "本文正被其他文章引用",
                'aids'  => [$id],
            ];
            $json_data[] = $connect_item;
        } else {
            if (!in_array($id, $exist_item['aids'])) {
                $exist_item['aids'][] = $id;
                if (count($exist_item['aids']) >= 3) {
                    $exist_item['col'] = 'col-md-12';
                } else {
                    $exist_item['col'] = 'col-md-6';
                }
                $json_data[$exist_key] = $exist_item;
            }
        }
        $article_connected->json = json_encode($json_data);
        $article_connected->save();
    }

    return $article;
});

//获取文章所有相关片段数据
Route::get('/article/{id}/lists', function (Request $request, $id) {
    $article   = Article::findOrFail($id);
    $contoller = new ArticleController();
    return $contoller->get_json_lists($article);
});

//删除文章相关片段数据
Route::get('/article/{id}/del-{key}', function (Request $request, $id, $key) {
    $article = Article::findOrFail($id);
    $data    = json_decode($article->json, true);
    if (empty($data)) {
        $data = [];
    }
    $data_new = [];
    foreach ($data as $k => $list) {
        if ($k == $key) {
            continue;
        }
        $data_new[] = $list;
    }

    $article->json = json_encode($data_new);
    $article->save();

    //TODO:: 删除被引用文章的关系

    return $data_new;
});

//获取文章相关片段数据
Route::get('/article/{id}/{key}', function ($id, $key) {
    $article = Article::findOrFail($id);
    $json    = json_decode($article->json, true);
    if (array_key_exists($key, $json)) {
        $data = $json[$key];
        if (empty($data['type']) || $data['type'] == 'single_list') {
            $items = [];
            if (is_array($data['aids'])) {
                foreach ($data['aids'] as $aid) {
                    $article = Article::find($aid);
                    if ($article) {
                        $items[] = [
                            'id'        => $article->id,
                            'title'     => $article->title,
                            'image_url' => get_img($article->image_url),
                        ];
                    }
                }
            }
            $data['items'] = $items;
        }

        return $data;
    }
    return null;
});

// ----------------------------------------------

//保存视频相关片段数据
Route::post('/video/{id}/json', function (Request $request, $id) {
    $video = Video::findOrFail($id);
    $data  = json_decode($video->json);
    if (empty($data)) {
        $data = [];
    }
    $data[]      = $request->all();
    $video->json = json_encode($data);
    $video->save();

    return $video;
});

//获取视频所有相关片段数据
Route::get('/video/{id}/lists', function (Request $request, $id) {
    $video     = Video::findOrFail($id);
    $contoller = new VideoController();
    return $contoller->get_json_lists($video);
});

//删除视频相关片段数据
Route::get('/video/{id}/del-{key}', function (Request $request, $id, $key) {
    $video = Video::findOrFail($id);
    $data  = json_decode($video->json);
    if (empty($data)) {
        $data = [];
    }
    $data_new = [];
    foreach ($data as $k => $list) {
        if ($k == $key) {
            continue;
        }
        $data_new[] = $list;
    }

    $video->json = json_encode($data_new);
    $video->save();

    return $data_new;
});

//获取视频相关片段数据
Route::get('/video/{id}/{key}', function ($id, $key) {
    $video = Video::findOrFail($id);
    $json  = json_decode($video->json, true);
    if (array_key_exists($key, $json)) {
        $data = $json[$key];
        if (empty($data['type']) || $data['type'] == 'single_list') {
            $items = [];
            if (is_array($data['aids'])) {
                foreach ($data['aids'] as $aid) {
                    $video = Video::find($aid);
                    if ($video) {
                        $items[] = [
                            'id'        => $video->id,
                            'title'     => $video->title,
                            'image_url' => get_img($video->cover),
                        ];
                    }
                }
            }
            $data['items'] = $items;
        }

        return $data;
    }
    return null;
});

<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Http\Controllers\Controller;
use App\Image;
use App\Traffic;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function login(Request $request)
    {
        if (Auth::attempt([
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ])) {
            return Auth::user();
        }
        return null;
    }

    public function register(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);
        if (!str_contains($data['email'], '@')) {
            return 'email format incorrect';
        }
        if (strlen($data['password']) < 6) {
            return 'password too short';
        }
        $user = User::firstOrNew([
            'email' => $data['email'],
        ]);
        $user->name     = $data['name'];
        $user->password = bcrypt($data['password']);
        $user->save();
        return $user;
    }

    public function getArticles(Request $request, $id)
    {
        $query = Article::with('category')->where('user_id', $id)->orderBy('id', 'desc');
        if ($request->get('title')) {
            $query = $query->where('title', 'like', '%' . $request->get('title') . '%');
        }
        $articles = $query->paginate(12);
        foreach ($articles as $article) {
            $article->image_url = get_img($article->image_url);
        }
        return $articles;
    }

    public function getVideos(Request $request, $id)
    {
        $query = Video::with('category')
            ->where('user_id', $id)
            ->where('count', '>=', 0)
            ->orderBy('id', 'desc');
        if ($request->get('title')) {
            $query = $query->where('title', 'like', '%' . $request->get('title') . '%');
        }
        $videos = $query->paginate(12);
        foreach ($videos as $video) {
            $video->path  = get_img($video->path);
            $video->cover = get_img($video->cover);
        }
        return $videos;
    }

    public function getImages(Request $request, $id)
    {
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
    }

    public function getInfoByName(Request $request, $name)
    {
        $user = User::where('name', $name)->first();
        return $user;
    }

    public function getInfo(Request $request, $id)
    {
        $user             = User::findOrFail($id);
        $user->avatar_url = $user->avatar();
        $data['user']     = $user;

        if (request()->ajax() || request()->get('debug')) {
            return $user;
        }

        $data['articles_count'] = Article::where('user_id', $user->id)->count();
        $data['traffic_count']  = Traffic::where('user_id', $user->id)->count();

        $data['articles_count_yesterday'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();
        $data['traffic_count_yesterday']  = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();

        $data['articles_count_today'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();
        $data['traffic_count_today']  = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();

        return $data;
    }

    public function unreads(Request $request)
    {
        return $request->user()->unreads();
    }

    public function recommend(Request $request)
    {
        $page_size = 5;
        $page      = rand(1, ceil(User::count() / $page_size));
        $users     = User::orderBy('id', 'desc')->skip(($page - 1) * $page_size)->take($page_size)->get();
        foreach ($users as $user) {
            $user->is_followed = Auth::user()->isFollow('users', $user->id);
        }

        return $users;
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        return $request->id;
    }

    public function update_avatar(Request $request, $id)
    {
        $user = $request->user();

        $dir        = '/storage/avatar/';
        $image_path = public_path($dir);

        if (!is_dir($image_path)) {
            mkdir($image_path, 0777, 1);
        }
        $filename = $user->id . '.jpg';
        $img      = \ImageMaker::make($request->file);
        if ($img->width() > 100) {
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $file_path = $image_path . $filename;
        $img->save($file_path);

        if ($user->avatar != $dir.$filename) {
            $user->avatar = $dir.$filename;
            $user->save();
        }

        return $user->avatar;
    }
}

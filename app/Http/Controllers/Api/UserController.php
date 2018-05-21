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
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        foreach ($users as $user) {
            $user->fillForJs();
        }
        return $users;
    }

    public function saveAvatar(Request $request)
    {
        $user        = $request->user();
        $avatar_path = '/storage/avatar/' . $user->id . '.jpg';

        //save avatar
        $img = \ImageMaker::make($request->avatar->path());
        if ($img->width() < $img->height()) {
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $img->resize(null, 100, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $img->crop(100, 100);
        $img->save(public_path($avatar_path));

        $user->update([
            'avatar' => $avatar_path,
        ]);

        return url($user->avatar);
    }

    public function save(Request $request)
    {
        $user = $request->user();
        $user->update($request->all());
        return $user;
    }

    public function editors(Request $request)
    {
        $users = User::orderBy('id', 'desc')->select('name', 'id')->paginate(100);
        return $users;
    }

    public function recommend(Request $request)
    {
        $page_size = 5;
        $page      = rand(1, ceil(User::count() / $page_size));
        $users     = User::orderBy('id', 'desc')->skip(($page - 1) * $page_size)->take($page_size)->get();
        foreach ($users as $user) {
            $user->fillForJs();

            if (Auth::check()) {
                $user->is_followed = $request->user()->isFollow('users', $user->id);
            }

        }
        return $users;
    }

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

    public function unreads(Request $request)
    {
        return $request->user()->unreads();
    }

    public function articles(Request $request, $id)
    {
        $query = Article::with('category')->where('user_id', $id)->orderBy('id', 'desc');
        if ($request->get('title')) {
            $query = $query->where('title', 'like', '%' . $request->get('title') . '%');
        }
        $articles = $query->paginate(12);
        foreach ($articles as $article) {
            $article->fillForJs();
        }
        return $articles;
    }

    public function videos(Request $request, $id)
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
            $video->fillForJs();
        }
        return $videos;
    }

    public function images(Request $request, $id)
    {
        $query = Image::where('user_id', $id)->where('count', '>', 0)->orderBy('updated_at', 'desc');
        if ($request->get('title')) {
            $query = $query->where('title', 'like', '%' . $request->get('title') . '%');
        }
        $images = $query->paginate(12);
        foreach ($images as $image) {
            $image->fillForJs();
        }
        return $images;
    }

    public function name(Request $request, $name)
    {
        $user = User::where('name', $name)->first();
        return $user;
    }

    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->fillForJs();
        $data['user'] = $user;

        $data['articles_count'] = Article::where('user_id', $user->id)->count();
        $data['traffic_count']  = Traffic::where('user_id', $user->id)->count();

        $data['articles_count_yesterday'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();
        $data['traffic_count_yesterday']  = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();

        $data['articles_count_today'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();
        $data['traffic_count_today']  = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();

        return $data;
    }
}

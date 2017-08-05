<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Video;
use App\Image;
use App\User;
use App\Traffic;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getVideos(Request $request, $id)
    {
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
        $user         = User::findOrFail($id);
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

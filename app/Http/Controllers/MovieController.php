<?php

namespace App\Http\Controllers;

use App\Movie;

class MovieController extends Controller
{
    /**
     * 首页数据的全部逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //TODO: 修复置顶电影专题
        // $stick_video_categories = get_stick_video_categories();
        // $video_category = [];
        // foreach ($stick_video_categories as $video_categories) {
        //     $video_id = $video_categories->id;
        //     $video_category[$video_categories->name]['video'] = Article::find($video_id)->orderby('count_likes','desc')->paginate(3);
        // }

        //热门电影专题
        $qb                   = Movie::orderBy('id');
        $movies               = $qb->take(3)->get();
        $data['在线美剧'] = $movies;
        $data['在线日剧'] = $qb->skip(1 * 3)->take(3)->get();
        $data['在线韩剧'] = $qb->skip(2 * 3)->take(3)->get();
        $data['在线港剧'] = $qb->skip(3 * 3)->take(3)->get();

        return view('movie.index')->with('data', $data);
    }

    public function show(Movie $movie)
    {

        return view('movie.show')->withMovie($movie);
    }
}

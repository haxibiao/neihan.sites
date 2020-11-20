<?php

namespace App\Http\Controllers;

use App\Movie;

class MovieController extends Controller
{

    public function search()
    {
        $query  = request()->get('q');
        $result = Movie::orderBy('id')->where('name', 'like', '%' . $query . '%')->paginate(10);
        $hot    = Movie::orderBy('id')->paginate(10);
        return view('movie.search')->withHot($hot)->withResult($result);
    }

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
        $qb = Movie::latest('id');

        $data['在线美剧'] = (clone $qb)->where('category_id', 2)->take(6)->get();
        $data['在线日剧'] = (clone $qb)->where('category_id', 1)->take(6)->get();
        $data['在线韩剧'] = (clone $qb)->where('category_id', 3)->take(6)->get();
        $data['在线港剧'] = (clone $qb)->where('category_id', 2)->take(6)->get(); //FIXME 港剧4 暂时无数据

        return view('movie.home')->with('data', $data);
    }

    public function riju()
    {
        $qb     = Movie::orderBy('id');
        $movies = (clone $qb)->where('category_id', 1)->paginate(24);
        return view('movie.region')->with('movies', $movies)->withCate("日剧")->with('cate_id', 1);
    }

    public function meiju()
    {
        $qb     = Movie::orderBy('id');
        $movies = (clone $qb)->where('category_id', 2)->paginate(24);
        return view('movie.region')->with('movies', $movies)->withCate("美剧")->with('cate_id', 2);
    }

    public function hanju()
    {
        $qb     = Movie::orderBy('id');
        $movies = (clone $qb)->where('category_id', 3)->paginate(24);
        return view('movie.region')->with('movies', $movies)->withCate("韩剧")->with('cate_id', 3);
    }

    public function gangju()
    {
        $qb     = Movie::orderBy('id');
        $movies = (clone $qb)->where('category_id', 2)->paginate(24); //FIXME 港剧4 暂时无数据
        return view('movie.region')->with('movies', $movies)->withCate("港剧")->with('cate_id', 4);
    }

    public function show(Movie $movie)
    {
        $qb   = Movie::latest('updated_at')->where('category_id', $movie->category_id);
        $more = $qb->take(6)->get();
        return view('movie.show')->withMovie($movie)->withMore($more);
    }
}

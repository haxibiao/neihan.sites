<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Like;
use App\Movie;
use App\MovieHistory;

class MovieController extends Controller
{

    public function search()
    { 
        $query  = request()->get('q');
        $result = Movie::orderBy('id')->where('name', 'like', '%' . $query . '%')->paginate(10);
        $hot    = Movie::orderBy('id')->paginate(10);
        return view('movie.search',[
            'hot' => $hot,
            'result' =>$result,
            'queryKeyword' => $query,
        ]);
    }

    /**
     * 首页数据的全部逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $qb             = Movie::latest('id');
        $hotMovies      = (clone $qb)->take(15)->get();
        $categoryMovies = [
            '热门美剧'  => [
                (clone $qb)->where('region', '美剧')->take(6)->get(),
                (clone $qb)->where('region', '美剧')->take(12)->get(),
                'meiju'
            ],
            '热门日剧'  => [
                (clone $qb)->where('region', '日剧')->latest('id')->take(6)->get(),
                (clone $qb)->where('region', '日剧')->latest('id')->take(12)->get(),
                'riju'
            ],
            '热门韩剧'  => [
                (clone $qb)->where('region', '韩剧')->latest('id')->take(6)->get(),
                (clone $qb)->where('region', '韩剧')->take(12)->get(),
                'hanju'
            ],
            '怀旧老港剧' => [
                (clone $qb)->where('region', '港剧')->latest('id')->take(6)->get(),
                (clone $qb)->where('region', '港剧')->latest('id')->take(12)->get(),
                'gangju'
            ],
        ];
        $cate_ranks = [
            '美剧' => [
                'cate'=>'meiju',
                'movies'=>(clone $qb)->where('region', '美剧')->offset(18)->take(8)->get()
            ],
            '日剧' => [
                'cate'=>'riju',
                'movies'=>(clone $qb)->where('region', '日剧')->offset(36)->take(8)->get()
            ],
            '韩剧' => [
                'cate'=>'hanju',
                'movies'=>(clone $qb)->where('region', '韩剧')->offset(18)->take(8)->get()
            ],
            '港剧' => [
                'cate'=>'gangju',
                'movies'=>(clone $qb)->where('region', '港剧')->offset(18)->take(8)->get()
            ],
        ];
        return view('movie.index', [
            'hotMovies'         => $hotMovies,
            'categoryMovies'    => $categoryMovies,
            'cate_ranks' => $cate_ranks,
        ]);
    }

    public function riju()
    {
        $order  = request()->get('order');
        if($order){
            $qb = Movie::orderByDesc($order);
        }else{
            $qb = Movie::orderBy('id');
        }
        $movies = (clone $qb)->where('region', "日剧")->paginate(24);
        return view('movie.region')->with('movies', $movies)->withCate("日剧")->with('cate_id', 1);
    }

    public function meiju()
    {
        $order  = request()->get('order');
        if($order){
            $qb = Movie::orderByDesc($order);
        }else{
            $qb = Movie::orderBy('id');
        }
        $movies = (clone $qb)->where('region', "美剧")->paginate(24);
        return view('movie.region')->with('movies', $movies)->withCate("美剧")->with('cate_id', 2);
    }

    public function hanju()
    {
        $order  = request()->get('order');
        if($order){
            $qb = Movie::orderByDesc($order);
        }else{
            $qb = Movie::orderBy('id');
        }
        $movies = (clone $qb)->where('region', "韩剧")->paginate(24);
        return view('movie.region')->with('movies', $movies)->withCate("韩剧")->with('cate_id', 3);
    }

    public function gangju()
    {
        $order  = request()->get('order');
        if($order){
            $qb = Movie::orderByDesc($order);
        }else{
            $qb = Movie::orderBy('id');
        }
        $movies = (clone $qb)->where('region', "港剧")->paginate(24);
        return view('movie.region')->with('movies', $movies)->withCate("港剧")->with('cate_id', 4);
    }

    public function show(Movie $movie)
    {
        // dd($movie->play_url);
        $movie->hits = $movie->hits+1;
        $movie->save();
        $qb   = Movie::latest('updated_at')->where('region', $movie->region);
        $more = $qb->take(6)->get();
        if($user = checkUser()){
            MovieHistory::updateOrCreate([
                'user_id'  => $user->id,
                'movie_id' => $movie->id,
            ], [
                'last_watch_time' => now(),
            ]);
            $movie->favorited = Favorite::where('user_id', $user->id)
            ->where('faved_id', $movie->id)->where('faved_type', 'movies')->exists();
            $movie->liked = Like::where('user_id', $user->id)
            ->where('likable_id', $movie->id)->where('likable_type', 'movies')->exists();
        }
        $movie->likes = Like::where('likable_id', $movie->id)->where('likable_type', 'movies')->count();
        return view('movie.show')->withMovie($movie)->withMore($more);
    }

    public function favorites()
    {
        $user = \Auth::user();
        $type = request()->get('type');

        if ($type == 'like') {
            $movieID = Like::where([
                'user_id'       => $user->id,
                'likable_type' => 'movies',
            ])->select('likable_id')->get()->pluck('likable_id');
            $cate = "喜欢";
        } else if ($type == 'favorite') {
            $movieID = Favorite::where([
                'user_id' => $user->id,
                'faved_type' => 'movies',
            ])->select('faved_id')->get()->pluck('faved_id');
            $cate = "收藏";
        } else if ($type == 'history') {
             $movieID = MovieHistory::where([
                 'user_id' => $user->id,
             ])->select('movie_id')->get()->pluck('movie_id');
             $cate = "足迹";
        }

        $movies = Movie::whereIn('id', $movieID)->paginate(18);
        return view('movie.favorites', [
            'cate'   => $cate,
            'movies' => $movies,
        ]);
    }
}

<?php

namespace App\Http\Controllers;


class MovieController extends Controller
{
    /**
     * 首页数据的全部逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('movie.index');
    }
}

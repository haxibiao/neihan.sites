<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['loginAs', 'robot']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        //不再单个网站统计编辑工作量和流量

        return view('home')->withUser($user);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile')->withUser($user);
    }
}

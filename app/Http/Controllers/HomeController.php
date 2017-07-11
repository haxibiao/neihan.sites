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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id','desc')->take(10)->get();
        $user  = Auth::user();
        return view('home')->withUsers($users)->withUser($user);
    }

    public function profile() {
        $user  = Auth::user();
        return view('profile')->withUser($user);
    }
}

<?php

namespace App\Http\Controllers;

use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index()
    {
        return view('admin.index');
    }

    public function users()
    {
        $users = User::paginate(20);
        return view('admin.users')->withUsers($users);
    }
}

<?php

namespace App\Http\Controllers;

use App\Article;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user     = User::findOrFail($id);
        $articles = Article::where('user_id', $user->id)->paginate(10);
        return view('user.show')->withUser($user)->withArticles($articles);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit')->withUser($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->except('__token'));

        //TODO::save avatar url ...
        $file       = $request->file('avatar');
        $local_path = public_path('storage/avatar/');
        if (!is_dir($local_path)) {
            mkdir($local_path, 0777, 1);
        }
        $filename = $user->id . '.jpg';
        $file->move($local_path, $filename);

        //resize
        $full_path = $local_path . $filename;
        $img       = \ImageMaker::make($full_path);
        $img->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($full_path);

        $user->avatar = '/storage/avatar/' . $filename;
        $user->save();

        return redirect()->to('/user/' . $user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->status = -1;
            $user->save();
        }
        return redirect()->back();
    }
}

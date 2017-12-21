<?php

namespace App\Http\Controllers;

use App\Article;
use App\Favorite;
use App\Follow;
use App\Like;
use App\User;
use App\Video;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'videos', 'articles']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(24);
        return view('user.index')->withUsers($users);
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
        $user                       = User::findOrFail($id);
        $data['articles']           = Article::where('user_id', $user->id)->orderBy('id', 'desc')->where('status', 1)->paginate(10);
        $data['articles_commented'] = Article::where('user_id', $user->id)->orderBy('updated_at', 'desc')->where('status', 1)->paginate(10);
        $data['articles_hot']       = Article::where('user_id', $user->id)->orderBy('hits', 'desc')->where('status', 1)->paginate(10);
        return view('user.show')
            ->withUser($user)
            ->withData($data);
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
        $user->update($request->all());

        //TODO::save avatar url ...
        $file = $request->file('avatar');
        if ($file) {
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
        }
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

    public function drafts($id)
    {
        $user             = User::findOrFail($id);
        $data['articles'] = Article::where('user_id', $user->id)->orderBy('id', 'desc')->where('status', 0)->paginate(10);
        return view('user.drafts')
            ->withUser($user)
            ->withData($data);
    }

    public function articles($id)
    {
        $user             = User::findOrFail($id);
        $data['articles'] = Article::where('user_id', $user->id)->orderBy('id', 'desc')->where('status', 1)->paginate(10);
        return view('user.articles')
            ->withUser($user)
            ->withData($data);
    }

    public function videos($id)
    {
        $user           = User::findOrFail($id);
        $data['videos'] = Video::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        return view('user.videos')
            ->withUser($user)
            ->withData($data);
    }

    public function favorites(Request $request)
    {
        $user                 = $request->user();
        $fav_articles         = Favorite::with('faved')->where('user_id', $user->id)->where('faved_type', 'articles')->orderBy('id', 'desc')->paginate(10);
        $data['fav_articles'] = $fav_articles;

        return view('user.favorites')
            ->withUser($user)
            ->withData($data);
    }

    public function likes(Request $request)
    {
        $user = $request->user();

        $data['like_articles'] = Like::with('liked')
            ->where('user_id', $user->id)
            ->where('liked_type', 'articles')
            ->orderBy('id', 'desc')
            ->paginate(10)
        ;

        //TODO:: ajax loading

        $data['followed_categories'] = Follow::with('followed')
            ->where('user_id', $user->id)
            ->where('followed_type', 'categories')
            ->orderBy('id', 'desc')
            ->paginate(10)
        ;

        $data['followed_collections'] = Follow::with('followed')
            ->where('user_id', $user->id)
            ->where('followed_type', 'collections')
            ->orderBy('id', 'desc')
            ->paginate(10)
        ;

        return view('user.home')
            ->withData($data)
            ->withUser($user);
    }
}

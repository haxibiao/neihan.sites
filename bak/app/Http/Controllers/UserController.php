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
        // $this->middleware('auth.editor', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('is_editor','desc')->orderBy('id', 'desc')->paginate(24);

        if(AjaxOrDebug() && request('article_id')){
             $users=[];
             $article =Article::findOrFail(request('article_id'));
             $comments=$article->comments()->where('commentable_type','article_author')->get();
             foreach($comments as $comment){
                 $users[]=$comment->user;
             }
             $users[] = $article->user;
             return $users;
        }
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
        $user = User::findOrFail($id);

        //文章
        $articles = Article::where('user_id', $user->id)
            ->with('user')->with('category')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->paginate(10);
        if (ajaxOrDebug() && request('articles')) {
            foreach ($articles as $article) {
                $article->fillForJs();
            }
            return $articles;
        }
        $data['articles'] = $articles;

        //最新评论
        $articles = Article::where('user_id', $user->id)
            ->with('user')->with('category')
            ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        if (ajaxOrDebug() && request('commented')) {
            foreach ($articles as $article) {
                $article->fillForJs();
            }
            return $articles;
        }
        $data['commented'] = $articles;

        //热门
        $articles = Article::where('user_id', $user->id)
            ->with('user')->with('category')
            ->where('status', 1)
            ->orderBy('hits', 'desc')
            ->paginate(10);
        if (ajaxOrDebug() && request('hot')) {
            foreach ($articles as $article) {
                $article->fillForJs();
            }
            return $articles;
        }
        $data['hot'] = $articles;

        //动态
        $actions = $user->actions()
            ->with('user')
            ->with('actionable')
            ->orderBy('id', 'desc')
            ->paginate(10);
        foreach ($actions as $action) {
            switch (get_class($action->actionable)) {
                case 'App\Article':
                    # code...
                    break;
                case 'App\Comment':
                    $action = $action->load('actionable.commentable.user');
                    break;
                case 'App\Favorite':
                    $action = $action->load('actionable.faved.user');
                    break;
                case 'App\Like':
                    $action = $action->load('actionable.liked.user');
                    break;
                case 'App\Follow':
                    if (get_class($action->actionable->followed) == 'App\Category') {
                        $action = $action->load('actionable.followed.user');
                    } else {
                        $action = $action->load('actionable.followed');
                    }
                    break;
            }
        }
        $data['actions'] = $actions;

        $data['actions_article'] = Article::where('user_id', $user->id)->orderBy('id', 'desc')->where('status', 1)->paginate(40);

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

            $articles =$user->articles;

            foreach($articles as $article){
                 $article->status =-1;
                 $article->save();
            }

            $comments=$user->comments;

            foreach($comments as $comment){
                 $comments->delete();
            }
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
        $fav_articles         = Favorite::with('faved')->with('user')->where('user_id', $user->id)->where('faved_type', 'articles')->orderBy('id', 'desc')->paginate(10);
        $data['fav_articles'] = $fav_articles;

        return view('user.favorites')
            ->withUser($user)
            ->withData($data);
    }

    public function likes(Request $request)
    {
        $user = $request->user();

        $data['like_articles'] = Like::with('liked')->with('user')
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

        $data['followed_collections'] = Follow::with('foldatlowed')
            ->where('user_id', $user->id)
            ->where('followed_type', 'collections')
            ->orderBy('id', 'desc')
            ->paginate(10)
        ;

        //动态
        $actions = $user->actions()
            ->with('user')
            ->with('actionable')
            ->orderBy('id', 'desc')
            ->paginate(10);
        foreach ($actions as $action) {
            switch (get_class($action->actionable)) {
                case 'App\Article':
                    # code...
                    break;
                case 'App\Comment':
                    $action = $action->load('actionable.commentable.user');
                    break;
                case 'App\Favorite':
                    $action = $action->load('actionable.faved.user');
                    break;
                case 'App\Like':
                    $action = $action->load('actionable.liked.user');
                    break;
                case 'App\Follow':
                    if (get_class($action->actionable->followed) == 'App\Category') {
                        $action = $action->load('actionable.followed.user');
                    } else {
                        $action = $action->load('actionable.followed');
                    }
                    break;
            }
        }
        $data['actions'] = $actions;

        $data['actions_article'] = Article::where('user_id', $user->id)->orderBy('id', 'desc')->where('status', 1)->paginate(40);

        return view('user.home')
            ->withData($data)
            ->withUser($user);
    }

    public function setting()
    {
        return view('user.setting');
    }

    public function wallet(Request $request)
    {
        $user         = $request->user();
        $transactions = $user->transactions()->orderBy('id', 'desc')->paginate(10);
        return view('user.wallet')
            ->withUser($user)
            ->withTransactions($transactions);
    }

    public function follows(Request $request,$id)
    {
        $user =User::findOrFail($id);

        $data['follows']=$user->followingUsers()->with('user')->orderBy('id','desc')->paginate(10);
        $data['followers']=$user->follows()->with('user')->orderBy('id','desc')->paginate(10);

        return view('user.follow')
        ->withData($data)
        ->withUser($user);
    }
}
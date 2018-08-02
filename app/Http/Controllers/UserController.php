<?php

namespace App\Http\Controllers;

use App\Article;
use App\Favorite;
use App\Follow;
use App\Question;
use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller {
	public function __construct() {
		$this->middleware('auth', ['only' => ['store', 'update', 'destroy', 'settings']]);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$users = User::orderBy('count_articles', 'desc')->paginate(24);

		//TODO:: need add debug and ajax ...

		return view('user.index')->withUsers($users);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$user = User::findOrFail($id);
		$user->followUsers = $user->followingUsers()->count();
		//作品
		$qb = Article::where('user_id', $user->id)
			->with('user')->with('category')
			->where('status', '>', 0)
			->orderBy('id', 'desc');
		$articles = smartPager($qb, 10);
		if (ajaxOrDebug() && request('articles')) {
			foreach ($articles as $article) {
				$article->fillForJs();
			}
			return $articles;
		}
		$data['articles'] = $articles;

		//最新评论
		$qb = Article::where('user_id', $user->id)
			->with('user')->with('category')
			->where('status', '>', 0)
			->orderBy('commented', 'desc');
		$articles = smartPager($qb, 10);
		if (ajaxOrDebug() && request('commented')) {
			foreach ($articles as $article) {
				$article->fillForJs();
			}
			return $articles;
		}
		$data['commented'] = $articles;

		//热门
		$qb = Article::where('user_id', $user->id)
			->with('user')->with('category')
			->where('status', '>', 0)
			->orderBy('hits', 'desc');
		$articles = smartPager($qb, 10);
		if (ajaxOrDebug() && request('hot')) {
			foreach ($articles as $article) {
				$article->fillForJs();
			}
			return $articles;
		}
		$data['hot'] = $articles;

		//动态
		$qb = $user->actions()
			->with('user')
			->with('actionable')
			->orderBy('created_at', 'desc');
		$actions = smartPager($qb, 10);
		if (ajaxOrDebug() && request('actions')) {
			foreach ($actions as $action) {
				$action->time = diffForHumansCN($action->created_at);
				$action = $action->fillForJs();
			}
			return $actions;
		}

		foreach ($actions as $action) {
			if (empty($action->actionable)) {
				continue;
			}
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

		//视频
		$qb = $user->videoPosts()
			->orderBy('updated_at', 'desc');
		$videos = smartPager($qb, 10);
		$data['videos'] = $videos;

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
	public function edit($id) {
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
	public function update(Request $request, $id) {
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
			$img = \ImageMaker::make($full_path);
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
	public function destroy($id) {
		$user = User::find($id);
		if ($user) {
			$user->status = -1;
			$user->save();
		}
		return redirect()->back();
	}

	public function drafts($id) {
		$user = User::findOrFail($id);
		$data['articles'] = Article::where('user_id', $user->id)->orderBy('id', 'desc')->where('status', 0)->paginate(10);
		return view('user.drafts')
			->withUser($user)
			->withData($data);
	}

	public function articles($id) {
		$user = User::findOrFail($id);
		$data['articles'] = Article::where('user_id', $user->id)->orderBy('id', 'desc')->where('status', 1)->paginate(10);
		return view('user.articles')
			->withUser($user)
			->withData($data);
	}

	public function videos($id) {
		$user = User::findOrFail($id);
		$data['videoPosts'] = $user->allVideoPosts()->orderBy('id', 'desc')->paginate(10);
		return view('user.videos')
			->withUser($user)
			->withData($data);
	}

	public function wallet(Request $request) {
		$user = $request->user();
		$transactions = $user->transactions()->orderBy('id', 'desc')->paginate(10);
		return view('user.wallet')
			->withUser($user)
			->withTransactions($transactions);
	}

	public function favorites(Request $request) {
		$user = $request->user();
		$data['articles'] = Favorite::with('faved')
			->where('user_id', $user->id)
			->where('faved_type', 'articles')
			->orderBy('id', 'desc')
			->paginate(10);
		$data['questions'] = Favorite::with('faved')
			->where('user_id', $user->id)
			->where('faved_type', 'questions')
			->orderBy('id', 'desc')
			->paginate(10);

		return view('user.favorites')
			->withUser($user)
			->withData($data);
	}
	public function questions(Request $request) {
		$user = Auth::user();
		$questions[] = null;
		$data['questions'] = Question::where('user_id', $user->id)->where('status', '>=', 0)->orderBy('id', 'desc')->paginate(10);

		$ans = $user->answers;
		foreach ($ans as $answer) {
			$question = $answer->question;
			if ($question->status >= 0) {
				$questions[] = $question;
			}
		}
		$questions = array_unique($questions);
		$data['answer_questions'] = $questions;
		return view('user.questions')->withUser($user)->withData($data);
	}

	public function likes(Request $request, $id) {
		$user = User::findOrFail($id);
		$user->followUsers = $user->followings()->where('followed_type', 'users')->count();

		$data['liked_articles'] = $user->likedArticles()
			->orderBy('id', 'desc')->paginate(10);

		$articles = $data['liked_articles'];

		//load more articles ...
		if (request()->ajax() || request('debug')) {
			$articles->transform(function ($article) {
				$article = $article->likedArticles();
				$article->fillForJs();
				$article->time_ago = $article->updatedAt();
				return $article;
			});
			return $articles;
		}

		$data['followed_categories'] = Follow::with('followed')
			->where('user_id', $user->id)
			->where('followed_type', 'categories')
			->orderBy('id', 'desc')
			->paginate(10);

		$data['followed_collections'] = Follow::with('followed')
			->where('user_id', $user->id)
			->where('followed_type', 'collections')
			->orderBy('id', 'desc')
			->paginate(10);

		return view('user.likes')
			->withUser($user)
			->withData($data);
	}

	public function follows(Request $request, $id) {
		$user = User::findOrFail($id);
		$user->followUsers = $user->followingUsers()->count();

		$data['follows'] = $user->followingUsers()->orderBy('id', 'desc')->paginate(10);
		$data['followers'] = $user->follows()->orderBy('id', 'desc')->paginate(10);

		return view('user.follows')
			->withUser($user)
			->withData($data);
	}

	public function settings(Request $request) {
		$user = $request->user();
		return view('user.settings')->withUser($user);
	}
}

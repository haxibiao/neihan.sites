<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Http\Controllers\Controller;
use App\Image;
use App\Traffic;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
	public function getSetting(Request $request) {
		$user = $request->user();
		//让设置页面的头像刷新浏览器缓存
		$user->avatar = $user->avatar . '?t=' . time();
		$user->balance = $user->balance();
		return $user;
	}

	public function index() {
		$users = User::orderBy('id', 'desc')->paginate(10);
		foreach ($users as $user) {
			$user->fillForJs();
		}
		return $users;
	}

	public function saveAvatar(Request $request) {
		$user = $request->user();
		if (!is_dir(public_path('/storage/avatar'))) {
			mkdir(public_path('/storage/avatar'), 0777, 1);
		}
		$avatar_path = '/storage/avatar/' . $user->id . '.jpg';
		if (!is_dir(public_path('/storage/avatar/'))) {
			mkdir(public_path('/storage/avatar/'), 0777, 1);
		}

		//save avatar
		$img = \ImageMaker::make($request->avatar->path());
		if ($img->width() < $img->height()) {
			$img->resize(100, null, function ($constraint) {
				$constraint->aspectRatio();
			});
		} else {
			$img->resize(null, 100, function ($constraint) {
				$constraint->aspectRatio();
			});
		}
		$img->crop(100, 100);
		$img->save(public_path($avatar_path));

		$user->update([
			'avatar' => $avatar_path,
		]);

		return url($user->avatar);
	}

	public function save(Request $request) {
		$user = $request->user();
		$user->update($request->all());
		return $user;
	}

	public function editors(Request $request) {
		$auth_user = $request->user();
		//获取我关注的人
		$followUserIds = \DB::table('follows')->where('user_id', $auth_user->id)
			->where('followed_type', 'users')
			->pluck('followed_id')->toArray();

		$followUserIds = array_unique($followUserIds);

		$users = User::whereIn('id', $followUserIds)->select('name', 'id')->paginate(100);

		//$users = User::orderBy('id', 'desc')->select('name', 'id')->paginate(100);
		return $users;
	}

	public function recommend(Request $request) {
		$page_size = 5;
		
		if (Auth::guard('api')->check()) {
			$users = User::whereIn('id',function($query){
				$query->select('id')
				->where('is_editor',1)->orWhere('is_signed',1);
			})->where('id','!=',Auth::guard('api')->user()->id);
		}else{
			$users = User::where('is_editor',1)->orWhere('is_signed',1);
		}

		$users = $users->orderByDesc('count_words')->orderByDesc('count_likes')
			->paginate($page_size);

		//当编辑和签约作者不足的时候 填充普通用户
		if($num = $page_size - $users->count()){
			$page = $request->get('page');

			$recommendUser = User::whereNotIn('id',$users->pluck('id'))->orderByDesc('count_words')
			->orderByDesc('count_likes')->paginate($num);
			
			$users = $users->merge($recommendUser);

			//当用户不足的时候 随机取用户
			if($users->count() == 0){
				$users = User::inRandomOrder()->take($page_size)->get();
			}
			
			$users = new LengthAwarePaginator($users,$users->count(),$users->count(),$page);
		}

		foreach ($users as $user) {
			$user->fillForJs();
			if (Auth::guard('api')->check()) {
				$user->is_followed = Auth::guard('api')->user()->isFollow('users', $user->id);
			}

		}
		return $users;
	}

	public function login(Request $request) {
		if (Auth::attempt([
			'email' => $request->get('email'),
			'password' => $request->get('password'),
		])) {
			return Auth::user();
		}
		return null;
	}

	public function register(Request $request) {
		$data = $request->only([
			'name',
			'email',
			'password',
		]);
		if (!str_contains($data['email'], '@')) {
			return 'email format incorrect';
		}
		if (strlen($data['password']) < 6) {
			return 'password too short';
		}
		$user = User::firstOrNew([
			'email' => $data['email'],
		]);
		if ($user->id) {
			throw new \Exception('Email already exists');
		}
		$user->name = $data['name'];
		$user->password = bcrypt($data['password']);
		$user->avatar = '/images/avatar-' . rand(1, 15) . '.jpg';
		$user->save();
		return $user;
	}

	public function unreads(Request $request) {
		return $request->user()->unreads();
	}

	public function articles(Request $request, $id) {
		$query = Article::with('category')->where('user_id', $id)->orderBy('id', 'desc');
		if ($request->get('title')) {
			$query = $query->where('title', 'like', '%' . $request->get('title') . '%');
		}
		$articles = $query->paginate(12);
		foreach ($articles as $article) {
			$article->fillForJs();
		}
		return $articles;
	}

	public function videos(Request $request, $id) {
		$query = Video::with('category')
			->where('user_id', $id)
			->where('count', '>=', 0)
			->orderBy('id', 'desc');
		if ($request->get('title')) {
			$query = $query->where('title', 'like', '%' . $request->get('title') . '%');
		}
		$videos = $query->paginate(12);
		foreach ($videos as $video) {
			$video->fillForJs();
		}
		return $videos;
	}

	public function images(Request $request, $id) {
		$query = Image::where('user_id', $id)->where('count', '>', 0)->orderBy('updated_at', 'desc');
		if ($request->get('title')) {
			$query = $query->where('title', 'like', '%' . $request->get('title') . '%');
		}
		$images = $query->paginate(12);
		foreach ($images as $image) {
			$image->fillForJs();
		}
		return $images;
	}

	public function name(Request $request, $name) {
		$user = User::where('name', $name)->first();
		return $user;
	}

	public function show(Request $request, $id) {
		$user = User::findOrFail($id);
		$user->fillForJs();
		$data['user'] = $user;

		$data['articles_count'] = Article::where('user_id', $user->id)->count();
		$data['traffic_count'] = Traffic::where('user_id', $user->id)->count();

		$data['articles_count_yesterday'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();
		$data['traffic_count_yesterday'] = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->subDay(1)->toDateString())->count();

		$data['articles_count_today'] = Article::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();
		$data['traffic_count_today'] = Traffic::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->count();
		return $data;
	}
	public function follows(Request $request, $id) {
		$user = User::findOrFail($id);
		if (ajaxOrDebug() && $request->get('followings')) {
			$data = smartPager($user->followingUsers()->orderBy('id', 'desc'), 10);
			foreach ($data as $follows) {
				$follows->user = $follows->followed;
				$follows->user->avatar = $follows->user->avatar();
				$follows->user->count_followings = $follows->user->followingUsers()->count();
				$follows->user->is_follow = is_follow('users', $follows->user->id);
			}
			return $data;
		}

		if (ajaxOrDebug() && $request->get('followers')) {
			$data = smartPager($user->follows()->orderBy('id', 'desc'), 10);
			foreach ($data as $followUser) {
				$followUser->user->avatar = $followUser->user->avatar();
				$followUser->user->count_followings = $followUser->user->followingUsers()->count();
				$followUser->user->is_follow = is_follow('users', $followUser->user->id);
			}
			return $data;
		}
		return $data;
	}

	/**
	 * @Author   XXM
	 * @DateTime 2018-07-31
	 * @param    Request    $request
	 * @param    [user]     $id
	 */
	public function relatedVideos(Request $request, $id) 
	{
		$user = User::findOrFail($id);

		$num = $request->get('num') ? $request->get('num') : 10;
		$data = $user->videoPosts()->paginate($num);
		
		foreach ($data as $article) {
			$article->fillForJs();
		}
		return $data;
	}

	/**
	 * @Author   XXM
	 * @DateTime 2018-07-31
	 * @param    Request    $request
	 * @param    [video]     $id
	 */
	public function sameVideos(Request $request, $id) 
	{
		$video = Video::with('article')->with('user')->findOrFail($id);
		$article = $video->article;
		$num = $request->get('num') ? $request->get('num') : 10;
		$data = $article->relatedVideoPostsQuery()->whereStatus(1)->where('id','!=',$article->id)->paginate($num);
		foreach ($data as $article) {
			$article->fillForJs();
		}
		return $data;
	}

	/**
	 * @Author      XXM
	 * @DateTime    2018-09-22
	 * @description            [返回你关注的和关注你的用户]
	 * @return      [users]     [description]
	 */
	public function relatedUsers()
	{
		$user = Auth::guard('api')->user();

		//如果是编辑则返回所有用户
		if($user->is_editor){
			return $users = User::select(['id','name'])->orderBy('count_articles','desc')->get()->toArray();
		}

		$followUsers = $user->followingUsers()->pluck('followed_id')->toArray();
		$userFans = $user->follows()->pluck('user_id')->toArray();

		$user_ids = array_merge($followUsers,$userFans);
		$user_ids = array_unique($user_ids);

		$users = User::whereIn('id',$user_ids)->select(['id','name'])->get()->toArray();
		return $users;
	}
}

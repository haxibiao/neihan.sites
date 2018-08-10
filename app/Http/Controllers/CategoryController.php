<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\CategoryRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller {
	public function __construct() {
		$this->middleware('auth', ['only' => ['store', 'create', 'update', 'destroy', 'edit']]);
		$this->middleware('auth.admin', ['only' => ['list']]);
	}

	/**
	专题管理列表
	 */
	function list(Request $request) {
		$qb = Category::where('status', '>=', 0)->orderBy('id', 'desc');
		$accurateCategory = '';
		if ($request->get('q')) {
			$keywords = $request->get('q');
			//精准匹配
			$accurateCategory = Category::where('status', '>=', 0)->where('name', $keywords)->orderBy('id', 'desc')->paginate(5);
			//模糊匹配
			$qb = Category::orderBy('id', 'desc')->where('status', '>=', 0)
				->where('name', 'like', "%$keywords%")->where('name', '!=', $keywords);
		}
		$type = $request->get('type') ?: 'article';

		switch ($type) {
		case 'question':
			$qb = $qb->where('count_questions', '>', 0);
			break;
		case 'video':
			$qb = $qb->where('count_videos', '>', 0);
			break;
		case 'snippet':
			$qb = $qb->where('count_snippets', '>', 0);
			break;
		default:
			$qb = $qb->where('count', '>=', 0);
			break;
		}
		$categories = $qb->paginate(12);
		return view('category.list')->withCategories($categories)->withAccurateCategory($accurateCategory);
	}

	/**
	 *   专题首页
	 */
	public function index(Request $request) {
		$qb = Category::where('status', '>=', 0)->orderBy('id', 'desc');
		$type = 'article';
		if ($request->get('type')) {
			$type = $request->get('type');
		}
		switch ($type) {
		case 'question':
			$qb = $qb->where('count_questions', '>', 0);
			break;

		default:
			$qb = $qb->where('count', '>=', 0);
			break;
		}

		//推荐
		$categories = $qb->orderBy('id', 'desc')->paginate(12);
		if (ajaxOrDebug() && request('recommend')) {
			foreach ($categories as $category) {
				$category->followed = $category->isFollowed();
				$category->count = $category->publishedWorks()->count();
			}
			return $categories;
		}
		$data['recommend'] = $categories;

		//热门
		//获取最近七天发布的Article 按照hits order by desc
		$week_start = Carbon::now()->subWeek()->startOfWeek()->toDateTimeString();
		$articles = Article::where('updated_at', '<=', $week_start)
			->where('status', '>=', 0)->whereNotNull('category_id')->selectRaw('category_id')
			->groupBy('category_id')->get()->toArray();
		$categories = Category::whereIn('id', $articles)->paginate(24);
		if (ajaxOrDebug() && request('hot')) {
			foreach ($categories as $category) {
				$category->followed = $category->isFollowed();
				$category->count = $category->count + $category->count_videos;
			}
			return $categories;
		}
		$data['hot'] = $categories;

		//TODO::  how to filter city categories ? ...
		//城市
		// $categories = $qb->paginate(24);
		// if (ajaxOrDebug() && request('city')) {
		//     foreach ($categories as $category) {
		//         $category->followed = $category->isFollowed();
		//     }
		//     return $categories;
		// }
		// $data['city'] = $categories;

		return view('category.index')
			->withData($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request) {
		$type = 'article';
		if ($request->get('type')) {
			$type = $request->get('type');
		}
		$user = Auth::user();
		return view('category.create')->withUser($user);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CategoryRequest $request) {
		$category = new Category($request->all());
		$category->save();
		//save logo
		$category->saveLogo($request);
		$category->save();

		//save admins ...
		$this->saveAdmins($category, $request);
		return redirect()->to('/category');
	}

	public function saveAdmins($category, $request) {
		$admins = json_decode($request->uids, true);
		//防止重复选人
		$admin_ids = [];
		if (!empty($admins)) {
			$admin_ids = array_unique(array_pluck($admins, 'id'));
		}
		$auth_id = $request->user()->id;
		if (!in_array($auth_id, $admin_ids)) {
			array_push($admin_ids, $auth_id);
		}
		if (is_array($admin_ids)) {
			$data = [];
			foreach ($admin_ids as $id) {
				$data[$id] = ['is_admin' => 1];
			}
			$category->admins()->sync($data);
		}
		//自己默认还是加成管理
		/*$category->admins()->syncWithoutDetaching([
	            $request->user()->id => ['is_admin' => 1],
*/
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$category = Category::findOrFail($id);
		return redirect()->to('/' . $category->name_en);
	}

	public function name_en(Request $request, $name_en) {
		$category = Category::where('name_en', $name_en)->firstOrFail();

		//最新评论
		$qb = $category->publishedWorks()
			->with('user')->with('category')
			->orderBy('commented', 'desc');
		$articles = smartPager($qb, 10);
		if (ajaxOrDebug() && $request->get('commented')) {
			foreach ($articles as $article) {
				$article->fillForJs();
				$article->time_ago = $article->updatedAt();
			}
			return $articles;
		}
		$data['commented'] = $articles;

		//作品
		$qb = $category->publishedWorks()
			->with('user')->with('category')
			->distinct('id')
			->orderBy('pivot_created_at', 'desc');
		$articles = smartPager($qb, 10);
		if (ajaxOrDebug() && $request->get('works')) {
			foreach ($articles as $article) {
				$article->fillForJs();
				$article->time_ago = $article->updatedAt();

			}
			return $articles;
		}
		$data['works'] = $articles;
		// dd($articles);

		//热门文章
		$qb = $category->publishedWorks()
			->with('user')->with('category')
			->orderBy('hits', 'desc');
		$articles = smartPager($qb, 10);
		if (ajaxOrDebug() && $request->get('hot')) {
			foreach ($articles as $article) {
				$article->fillForJs();
				$article->time_ago = $article->updatedAt();
			}
			return $articles;
		}
		$data['hot'] = $articles;

		//相关专题
		$related_category = User::find($category->user_id)->adminCategories->take(5);
		$data['related_category'] = $related_category;

		//记录日志
		$category->recordBrowserHistory();

		return view('category.name_en')
			->withCategory($category)
			->withData($data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id) {
		$type = 'article';
		if ($request->get('type')) {
			$type = $request->get('type');
		}
		$user = Auth::user();
		$category = Category::with('user')->find($id);
		if (!canEdit($category)) {
			abort(403);
		}
		// dd(json_encode($category->admins->pluck('name','id')));
		$categories = get_categories(0, $type, 1);
		return view('category.edit')->withUser($user)->withCategory($category)->withCategories($categories);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$category = Category::findOrFail($id);
		if (!canEdit($category)) {
			abort(403);
		}
		$category->update($request->all());
		//save logo
		$category->saveLogo($request);
		$category->updated_at = now();
		$category->save();
		//save admins ...
		$this->saveAdmins($category, $request);
		return redirect()->to('/category');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$category = Category::findOrFail($id);
		if (!canEdit($category)) {
			abort(403);
		}
		if ($category) {
			$count = \App\Article::where('category_id', $category->id)->where('status', '>', 0)->count();
			if ($count == 0) {
				if (Category::where('parent_id', $id)->count()) {
					return '该分类下还有分类，不能删除';
				}
				$category->status = -1;
				$category->save();
			} else {
				return '该分类下还有文章，不能删除';
			}
		}
		return redirect()->back();
	}
}

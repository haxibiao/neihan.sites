<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller {
	public function __construct() {
		$this->middleware('auth', ['except' => 'name_en']);
		$this->middleware('auth.editor', ['except' => 'name_en']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$type = 'article';
		if ($request->get('type')) {
			$type = $request->get('type');
		}
		$categories = get_categories(1, $type);
		$cate_ids = [];
		foreach ($categories as $category) {
			$cate_ids[] = $category->id;
		}
		$cates_miss = Category::with('user')->whereNotIn('id', $cate_ids)->where('type', $type)->get();
		return view('category.index')->withCategories($categories)->withCatesMiss($cates_miss);
	}

	public function name_en(Request $request, $name_en) {
	    $category=Category::where('name_en',$name_en)->firstOrFail();
	    $data['commented']=$category->articles()->where('status','>=',0)->orderBy('commented','desc')->paginate(10);
	    $data['collected']=$category->articles()->where('status','>=',0)->paginate(10);
	    $data['hot']=$category->articles()->where('status','>=',0)->orderBy('hits','desc')->paginate(10);

		return view('category.name_en')
           ->withCategory($category)
           ->withData($data)
		;
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
		$categories = get_categories(0, $type, 1);
		return view('category.create')->withUser($user)->withCategories($categories);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CategoryRequest $request) {
		$category = Category::firstOrNew($request->except('_token'));
		if ($category->parent_id == 0) {
			$category->level = 0;
		}
		$parent = Category::find($category->parent_id);
		if ($parent) {
			$parent->has_child = 1;
			$parent->save();
			$category->level = $parent->level + 1;
		}
		$category->save();
		return redirect()->to('/category');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$category = Category::with('user')->find($id);
		return view('category.show')->withCategory($category);
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
		$category = Category::find($id);
		$category->update($request->except('_token'));
		if ($category->parent_id == 0) {
			$category->level = 0;
		}

		$parent = Category::find($category->parent_id);
		if ($parent) {
			$parent->has_child = 1;
			$parent->save();
			$category->level = $parent->level + 1;
		}
		$category->save();

		return redirect()->to('/category');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$category = Category::find($id);
		if ($category) {
			$count = \App\Article::where('category_id', $category->id)->count();
			if ($count == 0) {
				if (Category::where('parent_id', $id)->count()) {
					return '该分类下还有分类，不能删除';
				}
				$category->delete();
			} else {
				return '该分类下还有文章，不能删除';
			}
		}
		return redirect()->back();
	}
    
    public function categories_hot(){
    	$data=[];
    	$data['hot']=Category::orderBy('count','desc')->take(8)->get();
    	$data['commend']=Category::orderBy('created_at','desc')->take(8)->get();
    	$data['city']=Category::orderBy('updated_at','desc')->take(8)->get();

    	return view('parts.list.categories_list')
    	->withData($data);
    }
}

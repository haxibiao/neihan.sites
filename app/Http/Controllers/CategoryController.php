<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'name_en']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = get_categories(1);
        $cate_ids   = [];
        foreach ($categories as $category) {
            $cate_ids[] = $category->id;
        }
        $cates_miss = Category::whereNotIn('id', $cate_ids)->get();
        return view('category.index')->withCategories($categories)->withCatesMiss($cates_miss);
    }

    public function name_en($name_en)
    {
        $category       = Category::where('name_en', $name_en)->first();
        $carousel_items = get_carousel_items($category->id);
        $articles       = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->where('category_id', $category->id)
            ->paginate(10);
        return view('category.name_en')
            ->withCategory($category)
            ->withCarouselItems($carousel_items)
            ->withArticles($articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user       = Auth::user();
        $categories = get_categories(0, 1);
        return view('category.create')->withUser($user)->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
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
    public function show($id)
    {
        $category = Category::with('user')->find($id);
        return view('category.show')->withCategory($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user       = Auth::user();
        $category   = Category::with('user')->find($id);
        $categories = get_categories(0, 1);
        return view('category.edit')->withUser($user)->withCategory($category)->withCategories($categories);
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
            $category->save();
        }

        return redirect()->to('/category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
        }
        return redirect()->back();
    }
}

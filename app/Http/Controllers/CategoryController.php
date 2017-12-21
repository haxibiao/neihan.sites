<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'name_en']);
        $this->middleware('auth.editor', ['except' => 'name_en']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = 'article';
        if ($request->get('type')) {
            $type = $request->get('type');
        }

        $categories = Category::orderBy('id', 'desc')->paginate(16);
        return view('category.index')->withCategories($categories);
    }

    public function name_en(Request $request, $name_en)
    {
        $category = Category::where('name_en', $name_en)->firstOrFail();

        //最新评论

        $articles = $category->articles()
            ->with('user')->with('category')
            ->where('status', '>=', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
        ;

        if ((request()->ajax() || request('debug')) && $request->get('commented')) {
            foreach ($articles as $article) {
                $article->fillForJs();

            }
            return $articles;
        }

        $data['commented'] = $articles;

        //最新收录

        $articles = $category->articles()
            ->with('user')->with('category')
            ->where('status', '>=', 0)
            ->orderBy('pivot_created_at', 'desc')
            ->paginate(10)
        ;
        if ((request()->ajax() || request('debug') && $request->get('collected'))) {
            foreach ($articles as $article) {
                $article->fillForJs();
            }
            return $articles;
        }

        $data['collected'] = $articles;

        //热门文章

        $articles = $category->articles()
            ->with('user')->with('category')
            ->where('status', '>=', 0)
            ->orderBy('hits', 'desc')
            ->paginate(10)
        ;
        if ((request()->ajax() || request('debug')) && $request->get('hot')) {
            foreach ($articles as $article) {
                $article->fillForJs();
            }
            return $articles;
        }

        $data['hot'] = $articles;

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
    public function create(Request $request)
    {
        $type = 'article';
        if ($request->get('type')) {
            $type = $request->get('type');
        }
        $user = Auth::user();
        // $categories = get_categories(0, $type, 1);
        return view('category.create')->withUser($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type     = 'article';
        $category = Category::firstOrNew($request->except(['_token', 'is_admin', 'submission']));

        if (!empty($request->logo)) {
            $category->logo = $this->category_logo($request->logo);
        }

        $category->type = $type;
        $category->save();

        //如果输入了管理员信息会往中间表中加入新的管理员信息.
        if ($request->is_admin) {
            foreach ($request->is_admin as $admin) {
                $user = User::where('name', $admin);
                $category->admins()->syncWithoutDetaching([
                    $user->id => [
                        'is_admin' => 1,
                    ],
                ]);
            }
        }

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
    public function edit(Request $request, $id)
    {
        $type = 'article';
        if ($request->get('type')) {
            $type = $request->get('type');
        }
        $user     = Auth::user();
        $category = Category::with('user')->find($id);

        return view('category.edit')->withUser($user)->withCategory($category);
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
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $count = \App\Article::where('category_id', $category->id)->count();
            if ($count == 0) {
                if (Category::where('parent_id', $id)->count()) {
                    return '该分类下还有分类，不能删除';
                }
                @unlink($category->logo);
                $small_logo = str_replace(".logo.jpg", ".logo.small.jpg", $category->logo);
                @unlink($small_logo);
                $category->delete();
            } else {
                return '该分类下还有文章，不能删除';
            }
        }
        return redirect()->back();
    }

    public function categories_hot()
    {
        $data            = [];
        $data['hot']     = Category::orderBy('count', 'desc')->take(8)->get();
        $data['commend'] = Category::orderBy('created_at', 'desc')->take(8)->get();
        $data['city']    = Category::orderBy('updated_at', 'desc')->take(8)->get();

        return view('parts.list.categories_list')
            ->withData($data);
    }

    public function category_logo($image)
    {
        $image_url = '/storage/img/' . str_random(5);
        $img       = \ImageMaker::make($image->path());
        $img->resize(300, 200);
        $img->crop(200, 200, 50, 0);

        $img->resize(180, 180);
        $logo = $image_url . '.logo.jpg';
        $img->save(public_path($logo));

        $img->resize(32, 32);
        $small_logo = $image_url . '.logo.small.jpg';
        $img->save(public_path($small_logo));

        return $logo;
    }
}

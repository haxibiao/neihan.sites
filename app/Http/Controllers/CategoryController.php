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
        $this->middleware('auth', ['except' => ['name_en','categories_hot']]);
        $this->middleware('auth.editor', ['except' => ['name_en','categories_hot']]);
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
            ->wherePivot('submit', '已收录')
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
        ;

        if (AjaxOrDebug() && $request->get('commented')) {
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
            ->wherePivot('submit', '已收录')
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
            ->wherePivot('submit', '已收录')
            ->orderBy('hits', 'desc')
            ->paginate(10)
        ;
        if (AjaxOrDebug() && $request->get('hot')) {
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
        $type           = 'article';
        $category       = Category::firstOrNew($request->except(['_token', 'is_admin', 'submission', 'uids']));
        $category->logo = '/logo/ainicheng.com.touch.jpg';

        if (!empty($request->logo)) {
            $category->logo = $this->category_logo($request->logo);
        }

        $category->type = $type;
        $category->save();

        $this->saveAdmins($request, $category);

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
        $category->update($request->except('_token', 'is_admin', 'submission', 'uids'));

        if (!empty($request->logo)) {
            $category->logo = $this->category_logo($request->logo);
        }

        $this->saveAdmins($request, $category, 1);

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
            if ($count == 0 && $category->logo != '/logo/ainicheng.com.touch.jpg') {
                if (Category::where('parent_id', $id)->count()) {
                    return '该分类下还有分类，不能删除';
                }
                @unlink($category->logo);
                $small_logo = str_replace(".logo.jpg", ".logo.small.jpg", $category->logo);
                @unlink($small_logo);
                $category->delete();
            } elseif ($category->logo == '/logo/ainicheng.com.touch.jpg') {
                $category->delete();
            } else {
                return '该分类下还有文章，不能删除';
            }
            return redirect()->back();
        }

    }

    public function categories_hot(Request $request)
    {
        $data = [];
        $type = 'article';

        $categories = Category::where('type', $type)->orderBy('count_follows', 'desc')->paginate(24);

        if (AjaxOrDebug() && request('hot')) {
            return $categories;
        }

        $data['commend'] = $categories;

        $categories = Category::where('type', $type)->orderBy('id', 'desc')->paginate(24);

        if (AjaxOrDebug() && request('recommend')) {
            return $categories; 
        }
        $data['hot'] = $categories;

        $categories = Category::where('type', $type)->paginate(24);
        if (AjaxOrDebug() && request('city')) {
            return $categories;
        }
        $data['city'] = $categories;

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

    public function saveAdmins($request, $category, $is_editor = null)
    {
        //save category user
        $category->admins()->syncWithoutDetaching([
                    $category->user->id => [
                        'is_admin' => 1,
                    ],
        ]);
        $category->authors()->syncWithoutDetaching([
                $category->user->id => [
                    'approved' => 1,
                ],
        ]);
        

        $admins = json_decode($request->uids);

        if (is_array($admins)) {
            if ($is_editor) {
                $admineds = $category->admins;
                foreach ($admineds as $admin) {
                    $category->admins()->detach();
                }
            }
            foreach ($admins as $admin) {
                $category->admins()->syncWithoutDetaching([
                    $admin->id => [
                        'is_admin' => 1,
                    ],
                ]);
            }
        }
    }
}

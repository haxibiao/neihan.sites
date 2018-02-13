<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getIndex(Request $request)
    {
        $type = 'article';
        if ($request->get('type')) {
            $type = $request->get('type');
        }
        $cates      = get_categories(1, $type);
        $categoreis = [];
        foreach ($cates as $cate) {
            if (!empty($cate->logo)) {
                $cate->logo   = get_img($cate->logo);
                $categories[] = $cate;
            }

        }
        return $categories;
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    public function commendCategory()
    {
       $page_size=4;
       $page=rand(1,ceil(Category::count()/$page_size));
       $categories=Category::orderBy('id','desc')->skip($page)->take($page_size)->get();
       return $categories;
    }
}

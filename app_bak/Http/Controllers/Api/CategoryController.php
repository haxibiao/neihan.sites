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

    public function commendCategory(Request $request)
    {
        if ($request->type) {
            $categories = Category::where('type', 'article')
                ->whereIn('name', [
                    '游戏',
                    '昵称',
                    '头像',
                    '句子',
                    '表情包',
                    '情感笔记',
                    '情侣日常',
                    '爱你城官方小课堂'])
                ->get();
            $categories = $categories->sortBy(function ($category, $key) {
                return strlen($category->name);
            });

        } else {
            $page_size  = 5;
            $page       = rand(1, ceil(Category::count() / $page_size));
            $categories = Category::orderBy('id', 'desc')->skip($page)->take($page_size)->get();
        }
        return $categories;
    }
}

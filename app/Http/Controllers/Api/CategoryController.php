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
}

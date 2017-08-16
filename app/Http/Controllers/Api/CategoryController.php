<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function getIndex()
    {
        $cates      = get_categories(1);
        $categoreis = [];
        foreach ($cates as $cate) {
            if (!empty($cate->logo)) {
                $cate->logo   = get_img($cate->logo);
                $categories[] = $cate;
            }

        }
        return $categories;
    }
}

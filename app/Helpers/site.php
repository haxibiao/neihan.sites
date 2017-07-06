<?php

function get_categories($full = 0, $for_parent = 0)
{
    $categories = [];
    if ($for_parent) {
        $categories[0] = null;
    }
    $category_items = \App\Category::where('status', '>=', 0)->get();
    foreach ($category_items as $item) {
        if ($item->level == 0) {
            $categories[$item->id] = $full ? $item : $item->name;
            if ($item->has_child) {
                foreach ($category_items as $item_sub) {
                    if ($item_sub->parent_id == $item->id) {
                        $categories[$item_sub->id] = $full ? $item_sub : ' -- ' . $item_sub->name;
                        foreach ($category_items as $item_subsub) {
                            if ($item_subsub->parent_id == $item_sub->id) {
                                $categories[$item_subsub->id] = $full ? $item_subsub : ' ---- ' . $item_subsub->name;
                            }
                        }
                    }
                }
            }
        }
    }
    $categories = \Illuminate\Support\Collection::make($categories);
    return $categories;
}

function get_carousel_items($category_id = 0)
{
    $carousel_items = [];
    $query          = App\Article::orderBy('id', 'desc')
        ->where('status', '>=', 0)
        ->where('is_top', 1);
    if ($category_id) {
        $query = $query->where('category_id', $category_id);
    }
    $top_pic_articles = $query->take(5)->get();
    $carousel_index   = 0;
    foreach ($top_pic_articles as $article) {
        $item = [
            'index'     => $carousel_index,
            'title'     => $article->title,
            'image_url' => empty($article->image_url) ? $article->image_top : $article->image_url,
        ];
        $carousel_items[] = $item;
        $carousel_index++;
    }
    return $carousel_items;
}

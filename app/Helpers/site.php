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
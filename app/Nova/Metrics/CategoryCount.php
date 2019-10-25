<?php

namespace App\Nova\Metrics;

use App\Article;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Metrics\Partition;

class CategoryCount extends Partition
{
    public $name = '分类下的视频数量前十个统计';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        //获取分类数量最大的前十个分类id
        $ten_top_category = Category::select(DB::raw('count(*) as categoryCount,category_id'))
            ->from('articles')
            ->where('type', 'video')
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderBy('categoryCount', 'desc')
            ->take(10)->get()->toArray();
        //去掉未分类
        $ten_top_category = array_filter($ten_top_category);

        return $this->count($request, Article::where('type', 'video')->whereIn('category_id', $ten_top_category), 'category_id')
            ->label(function ($value) {
                $category = Category::find($value);
                if (empty($category)) {
                    return "未分类";
                }
                return $category->name;
            });

    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'category-count';
    }
}

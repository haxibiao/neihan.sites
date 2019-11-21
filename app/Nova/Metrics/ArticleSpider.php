<?php

namespace App\Nova\Metrics;

use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class ArticleSpider extends Value
{

    public $name = "每日采集视频数";

    public function calculate(Request $request)
    {
        if ($request->range == 0) {
            $date = [
                date('Y-m-d'),
                date('Y-m-d', strtotime("+1 day")),
            ];
        }

        //自定义设置
        if (isset($date)) {
            $valueResult = $this->result($this->getCustomDateArticleCount($date));
        } else {
            $valueResult = $this->count($request, \App\Article::class);
        }
        return $valueResult->suffix('new article');
    }

    public function ranges()
    {
        return [
            0     => date('Y-m-d'),
            7     => '过去七天内',
            30    => '过去30天内',
            60    => '过去60天内',
            365   => '过去365天内',
            'MTD' => '本月到目前为止',
            'QTD' => '本季到目前为止',
            'YTD' => '到今年为止',
        ];
    }

    public function cacheFor()
    {
        return now()->addMinutes(30);
    }

    public function uriKey()
    {
        return 'article-spider';
    }

    public function getCustomDateArticleCount(array $date)
    {
        return \App\Article::query()->whereNotNull('source_url')->whereBetween('created_at', $date)->count();
    }
}

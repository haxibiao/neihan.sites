<?php

namespace App\Nova;

use App\Nova\Article;
use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Video extends Resource
{

    public static $model = 'App\Video';

    public static $title = 'id';

    public static $search = [
        'id',
    ];
    public static function label()
    {
        return '视频';
    }

    public static $group = '基础内容';

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            HasOne::make('出处', 'article', Article::class),
            BelongsTo::make('作者', 'user', User::class),
            // FIXME: 这里下次修改，本次作为初始化 nova
            Text::make('视频链接', function () {
                return '<a href="' . $this->url . '" class="no-underline dim text-primary font-bold">
                ' . $this->title . '
                </a>';
            })->asHtml(),
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }
}

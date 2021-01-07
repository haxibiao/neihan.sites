<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Stickable extends Resource
{

    public static $model = 'App\Stickable';

    public static $title = 'id';

    public static $group = '系统管理';

    public static $search = [
        'id', 'name',
    ];

    public static function label()
    {
        return "运营置顶";
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('名称', 'name'),
            Select::make('置顶类型', 'item_type')->options([
                'Video'   => '短视频',
                'Article' => '图文',
                'Movie'   => '电影',
            ]),
            Text::make('置顶id', 'item_id'),
            Text::make('页面', 'page'),
            Select::make('位置', 'area')->options([
                '上' => '上',
                '下' => '下',
                '左' => '左',
                '右' => '右',
            ]),
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

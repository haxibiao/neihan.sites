<?php

namespace App\Nova;

use App\AppConfig as AppAppConfig;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class AppConfig extends Resource
{

    public static $model = 'App\AppConfig';

    public static $title = 'id';

    public static $group = '系统管理';

    public static $search = [
        'id', 'name', 'group',
    ];

    public static function label()
    {
        return "开关";
    }

    public static function singularLabel()
    {
        return "开关";
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('组', 'group'),
            Text::make('功能', 'name'),
            Select::make('状态', 'state')->options([
                AppAppConfig::STATUS_ON  => '开启',
                AppAppConfig::STATUS_OFF => '关闭',
            ])->displayUsingLabels(),
            Code::make('App信息（没有就不填写，有的话请开发人员填写）', 'data')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
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

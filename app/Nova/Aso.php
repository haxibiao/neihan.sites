<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Aso extends Resource
{

    public static $model = 'App\Aso';

    public static $title = 'id';

    public static $group = '系统管理';

    public static $search = [
        'id', 'name', 'group',
    ];

    public static function label()
    {
        return "Aso";
    }

    public static function singularLabel()
    {
        return "Aso";
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('组', 'group'),
            Text::make('名称', 'name'),
            Text::make('值', 'value'),
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

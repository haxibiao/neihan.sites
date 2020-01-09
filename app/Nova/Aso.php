<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

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
            Text::make('值', function () {
                return strlen($this->value) > 50 ? mb_substr($this->value, 0, 50) . '...' : $this->value;
            })->onlyOnIndex()->hideWhenUpdating(),
            Textarea::make('值', 'value')->alwaysShow(),

            File::make('图片1', 'value')->store(
                function (Request $request, $model) {
                    $file = $request->file('value');

                    return $model->saveDownloadImage($file, $this->name);
                })->disableDownload(),
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

<?php

namespace App\Nova;

use App\User;
use Illuminate\Http\Request;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Audio extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Haxibiao\Question\Audio';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return '音频';
    }

    public static function singularLabel()
    {
        return '音频';
    }

    public static $category = "题库管理";

    /**
     * 预加载关联关系
     * @var array
     */
    public static $with = ['user'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('用户', 'user', 'App\Nova\User')->exceptOnForms(),
            Text::make('音频名称', 'name')->exceptOnForms(),
            Number::make('时长', 'duration')->exceptOnForms(),
            Url::make('播放地址', 'url')->label('点击查看')->clickableOnIndex(),
            Code::make('json', 'json')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE)->rules('required', 'max:4000'),
            Text::make('hash')->exceptOnForms(),
            Text::make('路径', 'path')->exceptOnForms(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}

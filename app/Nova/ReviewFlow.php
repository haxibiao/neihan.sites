<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class ReviewFlow extends Resource
{

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\ReviewFlow::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $group = '任务管理';

    public static function label()
    {
        return '任务模版';
    }

    public static function singularLabel()
    {
        return '任务模版';
    }

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
            Text::make('模版名', 'name'),
            Select::make('类型', 'type')->options(\App\ReviewFlow::getTypes())->displayUsingLabels(),
            Code::make('检查函数', 'check_functions')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
            Select::make('是否需要官方审核', 'need_offical_review')->options([
                1 => '需要',
                0 => '不需要',
            ])->displayUsingLabels(),
            Select::make('是否需要任务创建者审核', 'need_owner_review')->options([
                1 => '需要',
                0 => '不需要',
            ])->displayUsingLabels(),
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

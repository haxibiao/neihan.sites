<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Notice extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Notice';

    public static $displayInNavigation = false;

    public static $title = 'title';

    public static $search = [
        'title',
    ];

    public static $category = "系统管理";

    public static function label()
    {
        return '通告';
    }

    public static function singularLabel()
    {
        return '通告';
    }

    public static $with = ['user'];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('标题', 'title'),
            Textarea::make('内容', 'content'),
            DateTime::make('到期时间', 'expires_at'),
            BelongsTo::make('发表人', 'user', User::class)->exceptOnForms(),
            DateTime::make('创建时间', 'created_at')->exceptOnForms(),
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

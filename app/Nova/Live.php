<?php

namespace App\Nova;

use App\Nova\Resource;
use App\Nova\User;
use App\Nova\Video;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;

class Live extends Resource
{

    public static $category = "直播管理";

    public static function label()
    {
        return '直播';
    }

    public static function singularLabel()
    {
        return '直播';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Live';

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
        'id', 'user_id',
    ];

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
            BelongsTo::make('主播', 'user', User::class),
            BelongsTo::make('直播间', 'liveRoom', LiveRoom::class),
            BelongsTo::make('直播回放', 'video', Video::class),
            Number::make('观众数', 'count_users')->sortable(),
            Number::make('直播时长（秒）', 'live_duration')->sortable(),
            DateTime::make('直播开始时间', 'created_at'),
            Code::make('用户ID', 'data')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE),
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

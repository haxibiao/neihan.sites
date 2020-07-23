<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class CategoryUser extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Haxibiao\Question\CategoryUser';

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
        'user_id',
    ];

    /**
     * 指示资源是否是全局可搜索的。
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * 预加载关联关系
     * @var array
     */
    public static $with = ['user', 'category'];

    public static function label()
    {
        return '答题排重';
    }

    public static function singularLabel()
    {
        return '答题排重';
    }

    public static $category = "用户行为";

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
            Text::make('min_review_id')->exceptOnForms(),
            Text::make('max_review_id')->exceptOnForms(),
            Number::make('答对总数', 'correct_count')->exceptOnForms(),
            Number::make('答题总数', 'answer_count')->sortable()->exceptOnForms(),
            Number::make('每日审题数', 'reviews_today')->exceptOnForms(),
            Number::make('in_rank')->onlyOnDetail(),
            Code::make('rank_ranges')->json()->onlyOnDetail(),
            DateTime::make('创建时间', 'created_at')->onlyOnDetail(),
            DateTime::make('更新时间', 'updated_at')->onlyOnDetail(),
            BelongsTo::make('分类', 'category', 'App\Nova\Category')->exceptOnForms(),
            BelongsTo::make('用户', 'user', 'App\Nova\User')->exceptOnForms(),
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

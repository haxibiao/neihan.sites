<?php

namespace App\Nova;

use Haxibiao\Question\Question;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Answer extends Resource
{
    // public static $displayInNavigation = false;

    public static $model = 'Haxibiao\Question\Answer';

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static $globallySearchable = false;

    public static $category = "用户行为";

    public static function label()
    {
        return '答题';
    }

    public static function singularLabel()
    {
        return '答题';
    }

    public static $with = ['user', 'question'];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('用户', 'user', 'App\Nova\User'),
            BelongsTo::make('题目', 'question', 'App\Nova\Question'),
            Number::make('回答数', 'answered_count')->min(0),
            Number::make('正确数', 'correct_count')->min(0),
            Number::make('错误数', 'wrong_count')->min(0),
            DateTime::make('时间', 'updated_at')->exceptOnForms(),
            Text::make('题目review_id', 'question.review_id')->onlyOnDetail(),
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

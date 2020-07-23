<?php

namespace App\Nova;

use Haxibiao\Question\Nova\Filters\RecommendQuestion\RecommendQuestionCategoryFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class QuestionRecommend extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Haxibiao\Question\QuestionRecommend';

    public static $category = "题库管理";

    public static function label()
    {
        return '推荐题目';
    }

    public static function singluarLable()
    {
        return '推荐题目';
    }

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
        'id', 'question_id', 'rank',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        // $cateogory = $this->question->category;
        return  [
            ID::make()->sortable(),
            Number::make('权重', 'rank')->sortable(),
            BelongsTo::make('题目', 'question', 'App\Nova\Question'),
        ];
        //推荐题目没有关联分类
        // if (!empty($cateogory)) {
        //     array_push($fields, Text::make('分类', function () use ($category) {
        //         return '<a class="no-underline dim text-primary font-bold" href="categories/' . $category->id . '">' . $category->name . "</a>";
        //     })->asHtml());
        // }
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
        return [
            new RecommendQuestionCategoryFilter,
        ];
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

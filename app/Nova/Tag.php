<?php

namespace App\Nova;

use Haxibiao\Question\Tag as QuestionTag;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Tag extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Haxibiao\Question\Tag';

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
        'name',
    ];

    /**
     * @Author      XXM
     * @DateTime    2018-11-17
     * @description [资源显示的标签]
     * @return      [String]
     */
    public static function label()
    {
        return '标签';
    }

    /**
     * @Author      XXM
     * @DateTime    2018-11-17
     * @description [资源显示的单标签]
     * @return      [String]
     */
    public static function singularLabel()
    {
        return '标签';
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
            Text::make('名称', 'name'),
            Text::make('统计数', 'count')->exceptOnForms(),
            Text::make('排名', 'rank'),
            Select::make('状态', 'status')->options(QuestionTag::getStatuses())->displayUsingLabels(),
            BelongsTo::make('父标签', 'tag', 'App\Nova\Tag')->nullable()->exceptOnForms(),
            Text::make('备注', 'remark')->exceptOnForms(),
            BelongsTo::make('用户', 'user', 'App\Nova\Tag')->exceptOnForms(),
            MorphToMany::make('反馈', 'feedbacks', 'App\Nova\Feedback'),
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
        return [
            new \Haxibiao\Question\Nova\Actions\Tag\UpdateParentTag,
        ];
    }
}

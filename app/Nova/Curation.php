<?php

namespace App\Nova;

use Haxibiao\Question\Curation as QuestionCuration;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;

class Curation extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Haxibiao\Question\Curation';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'content';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
    public static $with = ['user', 'question'];

    /**
     * @Author      XXM
     * @DateTime    2018-11-15
     * @description [资源显示的标签]
     * @return      [String]
     */
    public static function label()
    {
        return '纠题';
    }

    /**
     * @Author      XXM
     * @DateTime    2018-11-15
     * @description [资源显示的单标签]
     * @return      [String]
     */
    public static function singularLabel()
    {
        return '纠题';
    }

    public static $category = "题库管理";

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
            Text::make('题目', 'question.description')->displayUsing(function ($value) {
                return sprintf(
                    '<a href="%s" class="no-underline dim"> %s </a>',
                    "./questions/" . $this->question_id,
                    str_limit($value, 20)
                );
            })->asHtml()->onlyOnIndex(),
            Text::make('题干', 'question.description')->displayUsing(function ($value) {
                return sprintf(
                    '<a href="%s" class="no-underline dim"> %s </a>',
                    "../questions/" . $this->question_id,
                    $value
                );
            })->asHtml()->hideFromIndex(),
            // BelongsTo::make('分类', 'category', category::class)->exceptOnForms(),
            Text::make('内容', 'content')->displayUsing(function ($value) {
                return str_limit($value, 20);
            })->onlyOnIndex(),
            Text::make('内容', 'content')->hideFromIndex(),
            Select::make('类型', 'type')->options(QuestionCuration::getTypes())->displayUsingLabels()->exceptOnForms(),
            Select::make('状态', 'status')->options(QuestionCuration::getStatuses())->displayUsingLabels(),
            Number::make('奖励', 'gold_awarded')->min(0)->exceptOnForms(),
            DateTime::make('创建时间', 'created_at')->exceptOnForms(),
            Textarea::make('备注', 'remark'),

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
        return [
            new \Haxibiao\Question\Nova\Filters\Curation\CurationStatusFilter,
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
        return [
            new \Haxibiao\Question\Nova\Actions\Curation\AuditCuration,
            new \Haxibiao\Question\Nova\Actions\Curation\CurationQuestionRemove,
            new \Haxibiao\Question\Nova\Actions\Curation\CurateQuestionsCategory,
        ];
    }
}

<?php

namespace App\Nova;

use App\Nova\Filters\FeedbackType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Feedback extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Feedback';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'content';

    public static $group = '用户管理';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'content',
    ];

    public static function label()
    {
        return "反馈";
    }

    public static function singularLabel()
    {
        return "反馈";
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
            BelongsTo::make('用户', 'user', User::class),
            Text::make('内容', 'content'),
            MorphMany::make('图片','images',\App\Nova\Image::class),
            Text::make('评论数', function () {
                return $this->comments()->count();
            }),
            Select::make('反馈状态', 'status')->options([
                0 => '待处理',
                1 => '已驳回',
                2 => '已处理',
            ])->displayUsingLabels(),
            Select::make('反馈类型', 'type')->options([
                0 => '使用反馈',
                1 => '好评反馈',
            ])->displayUsingLabels(),
            Text::make('联系方式', 'contact'),
            MorphMany::make('评论', 'comments', Comment::class),
            DateTime::make('反馈时间', 'created_at'),

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
            new FeedbackType
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
            new \App\Nova\Actions\Feedback\FeedbackComment,
            new \App\Nova\Actions\Feedback\FeedbackStatus,
        ];
    }
}

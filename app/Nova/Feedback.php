<?php

namespace App\Nova;

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
            Image::make('图片1', 'images')
                ->thumbnail(function () {
                    return $this->getImageItemUrl(0);
                })->preview(function () {
                return $this->getImageItemUrl(0);
            })->disableDownload(),
            Image::make('图片2', 'images')
                ->thumbnail(function () {
                    return $this->getImageItemUrl(1);
                })->preview(function () {
                return $this->getImageItemUrl(1);
            })->disableDownload(),
            Image::make('图片3', 'images')
                ->thumbnail(function () {
                    return $this->getImageItemUrl(2);
                })->preview(function () {
                return $this->getImageItemUrl(2);
            })->disableDownload(),
            Text::make('评论数', function () {
                return $this->comments()->count();
            }),
            Select::make('类型', 'status')->options([
                0 => '待处理',
                1 => '已驳回',
                2 => '已处理',
            ])->displayUsingLabels(),
            Text::make('联系方式', 'contact'),
            MorphMany::make('评论', 'comments', Comment::class),
            DateTime::make('反馈时间', 'created_at'),
            DateTime::make('置顶时间', 'top_at'),
            // Text::make('创建时间', function () {
            //     return time_ago($this->created_at);
            // }),
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
            new \App\Nova\Actions\Feedback\FeedbackComment,
            new \App\Nova\Actions\Feedback\FeedbackStatus,
        ];
    }
}

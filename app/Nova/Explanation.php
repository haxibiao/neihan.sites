<?php

namespace App\Nova;

use Halimtuhu\ArrayImages\ArrayImages;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;

class Explanation extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Haxibiao\Question\Explanation';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'description';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'content',
    ];

    public static $with = [
        'video',
    ];

    public static function label()
    {
        return '解析';
    }

    public static $category = '题库管理';

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
            Text::make('内容', function () {
                return $this->description;
            }),
            Textarea::make('内容', 'content')->onlyOnDetail(),
            BelongsTo::make('视频', 'video', 'App\Nova\Video')->exceptOnForms(),
            ArrayImages::make('图片', function () {
                return $this->image_array;
            })->exceptOnForms(),
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
            new \Haxibiao\Question\Nova\Filters\Explanation\ExplanationTypeFilter,
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

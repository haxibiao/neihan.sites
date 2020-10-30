<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphedByMany;
use App\Nova\Actions\Article\UpdatePost;
use Techouse\SelectAutoComplete\SelectAutoComplete;

class Collection extends Resource
{

    public static $group = '内容管理';

    public static $model = 'App\\Collection';

    public static $title = 'id';

    public static $displayInNavigation = true;

    public static $search = [
        'id', 'name',
    ];
    public static function label()
    {
        return "合集";
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
            Text::make('合集名', 'name'),
            Text::make('合集简介', 'description'),
            SelectAutoComplete::make('作者', 'user_id')->options(
                \App\User::pluck('name', 'id')->toArray()
            )->onlyOnForms(),
            BelongsTo::make('作者', 'user', User::class)->exceptOnForms(),
            MorphedByMany::make('合集内视频', 'posts', Post::class),
            Text::make('是否上架', function () {
                if ($this->status == 0) {
                    return "未上架";
                }
                return "上架";
            }),
            Text::make('创建时间', function () {
                return time_ago($this->created_at);
            }),
             Select::make('是否推荐','sort_rank')
            ->options([1 =>'已置顶',2=>'已推荐'])
            ->displayUsingLabels(),
            Image::make('封面图片', 'logo')
                ->store(function (Request $request, $model) {
                    $file = $request->file('logo');
                    return $model->saveDownloadImage($file);
                })->thumbnail(function () {
                    return $this->logo;
                })->preview(function () {
                    return $this->logo;
                })->disableDownload(),
            Text::make('文章数', 'count')->hideWhenCreating(),
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

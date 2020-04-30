<?php

namespace App\Nova;

use App\Category as AppCategory;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Category';

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
        'id', 'name', 'name_en',
    ];

    public static function label()
    {
        return "专题";
    }

    public static $group = '内容管理';

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
            Text::make('类型', 'type'),
            Text::make('分类名称', 'name'),
            Text::make('分类英文名', 'name_en'),
            Text::make('权重/排序', 'order')->onlyOnForms(),
            Select::make('官方专题', 'is_official')->options([1 => '是', 0 => '否'])->onlyOnForms(),
            Select::make('状态', 'status')->options([
                1 => '上架',
                0 => '隐藏',
            ])->displayUsingLabels(),

            // BelongsTo::make('上传图片', 'image', Image::class),
            Select::make('作者', 'user_id')->options(
                \App\User::where("id", "<", 30)->pluck("name", "id")
            )->displayUsingLabels(),

            Image::make('分类图片', 'logo')->store(
                function (Request $request, $model) {
                    $file = $request->file('logo');
                    return $model->saveDownloadImage($file);
                })->thumbnail(function () {
                return $this->novaLogo;
            })->preview(function () {
                return $this->novaLogo;
            })->disableDownload(),

            //BelongsTo::make('作者', 'user', User::class),
            HasMany::make('文章', 'hasManyArticles', Article::class),
            Text::make('视频数量', function () {
                return $this->containedVideoPosts()->count();
            }),
            Text::make('时间', function () {
                return time_ago($this->created_at);
            }),
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
        return [
            (new \Hxb\CategoryCount\CategoryCount)
                ->withName("分类下的视频数量前十个统计")
                ->withLegend("视频数量")
                ->withColor("#FF00FF")
                ->withData(AppCategory::getTopCategory(10)),
        ];
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
            new \App\Nova\Filters\Content\TaskStatusType,
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

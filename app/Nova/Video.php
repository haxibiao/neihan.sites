<?php

namespace App\Nova;

use App\Nova\Article;
use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Video extends Resource
{

    public static $model = 'App\Video';

    public static $title = 'id';

    public static $search = [
        'id',
    ];
    public static function label()
    {
        return '视频';
    }

    public static $group = '内容管理';

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            HasOne::make('出处', 'article', Article::class),
            BelongsTo::make('作者', 'user', User::class),
            Image::make('封面', 'cover')
                ->thumbnail(function () {
                    return $this->coverUrl;
                })->preview(function () {
                return $this->coverUrl;
            })->disableDownload(),
            Text::make('status', 'status')->onlyOnDetail(),
            Text::make('path', 'path')->onlyOnDetail(),
            Text::make('disk', 'disk')->onlyOnDetail(),
            Text::make('hash', 'hash')->hideFromIndex(),
            Text::make('标题', 'title')->onlyOnForms(),
            Text::make('视频链接', function () {
                return '<a href="' . $this->url . '" class="no-underline dim text-primary font-bold">
                ' . '视频链接' . '</a>';
            })->asHtml(),
        ];
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }
}

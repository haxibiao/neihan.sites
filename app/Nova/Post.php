<?php

namespace App\Nova;

use App\Nova\Actions\Article\AuditVideoPostSubmitStatus;
use App\Nova\Filters\Article\ArticleSubmitFilter;
use App\Nova\Filters\ArticleSource;
use App\Scopes\ArticleSubmitScope;
use Halimtuhu\ArrayImages\ArrayImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Post extends Resource
{
    //public static $title = 'description';

    public static $model = 'App\\Post';

    public static $group = '内容管理';

    //    public static $search = [
    //        'id', 'description',
    //    ];

    public static function label()
    {
        return "动态";
    }

    public static function singularLabel()
    {
        return "动态";
    }

    public function fields(Request $request)
    {
        $user = getUser();
        return [
            ID::make()->sortable(),
            Textarea::make('文章内容', 'content')->rules('required')->hideFromIndex(),
            BelongsTo::make('作者', 'user', 'App\Nova\User')->exceptOnForms(),
            BelongsTo::make('视频', 'video', Video::class)->exceptOnForms(),
            Text::make('描述', 'description')->exceptOnForms(),
            Text::make('热度', 'hot')->exceptOnForms(),
            Text::make('点赞', 'count_likes')->exceptOnForms(),
            Text::make('评论', 'count_comments')->exceptOnForms(),
            Select::make('状态', 'status')->options([
                1  => '公开',
                0  => '草稿',
                -1 => '下架',
            ])->displayUsingLabels(),

            Text::make('视频', function () {
                if ($this->video) {
                    return "<div style='width:150px; overflow:hidden;'><video controls style='widht:150px; height:300px' src='" . $this->video->url . "?t=" . time() . "'></video></div>";
                }
                return '';
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

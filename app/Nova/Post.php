<?php

namespace App\Nova;

use App\Issue;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Fields\Image;

class Post extends Resource
{

    public static $group = "内容管理";

    public static function label()
    {
        return 'Post';
    }

    public static function singularLabel()
    {
        return 'Post';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Post';

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
        'id', 'user_id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $user = getUser();
        return [
            ID::make()->sortable(),
            Text::make('相关文字', function () {
                $text = str_limit($this->description);
                return '<a style="width: 300px" href="articles/' . $this->id . '">' . $text . "</a>";
            })->asHtml()->onlyOnIndex(),
            Text::make('内容', 'content')->hideFromIndex()->hideWhenCreating(),

            Text::make('点赞数', 'count_likes')->hideWhenCreating(),

            Textarea::make('文章内容', 'description')->rules('required')->hideFromIndex(),
            Select::make('状态', 'status')->options([
                1  => '公开',
                0  => '草稿',
                -1 => '下架',
            ])->displayUsingLabels(),
            BelongsTo::make('作者', 'user', 'App\Nova\User')->exceptOnForms(),

            Text::make('时间', function () {
                return time_ago($this->created_at);
            })->onlyOnIndex(),
            // Number::make('总评论数', 'count_comments')->exceptOnForms()->sortable(),
            File::make('上传视频', 'video_id')->onlyOnForms()->store(
                function (Request $request, $model) {
                    $file      = $request->file('video_id');
                    $validator = Validator::make($request->all(), [
                        'video' => 'mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime',
                    ]);
                    if ($validator->fails()) {
                        return '视频格式有问题';
                    }
                    return \App\Video::uploadVod($file);
                }
            ),
            BelongsTo::make('视频', 'video', Video::class)->exceptOnForms(),
            Image::make('图片', 'video.cover')->thumbnail(
                function () {
                    return $this->cover;
                }
            )->preview(
                function () {
                    return $this->cover;
                }
            ),

            // BelongsTo::make('问题', 'issue', Issue::class),
            Text::make('视频时长', function () {
                if ($video = $this->video) {
                    return $video->duration;
                }
            }),
            Text::make('视频', function () {
                if ($this->video) {
                    return "<div style='width:150px; overflow:hidden;'><video controls style='widht:150px; height:300px' src='" . $this->video->url . "?t=" . time() . "'></video></div>";
                }
                return '';
            })->asHtml(),
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

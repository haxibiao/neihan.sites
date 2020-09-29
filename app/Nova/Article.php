<?php

namespace App\Nova;

use App\Nova\Actions\Article\AuditVideoPostSubmitStatus;
use App\Nova\Filters\ArticleSource;
use App\Nova\Filters\Article\ArticleSubmitFilter;
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
use Laravel\Nova\Resource;

class Article extends Resource
{

    public static $displayInNavigation = false;

    public static $model = 'App\\Article';

    public static $title = 'title';

    public static $group = '内容管理';

    public static $with = ['user', 'category', 'video'];
    public static $search = [
        'id', 'title',
    ];

    public static function label()
    {
        return "动态";
    }

    public static function singularLabel()
    {
        return "动态";
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        //忽略Article在Submit字段上的的全局作用域
        return $query->withoutGlobalScope(ArticleSubmitScope::class)->whereIn('type', ['post', 'issue', 'video']);
    }

    public function fields(Request $request)
    {
        $user = getUser();
        return [
            ID::make()->sortable(),
            Text::make('相关文字', function () {
                $text = str_limit($this->body);
                return '<a style="width: 300px" href="articles/' . $this->id . '">' . $text . "</a>";
            })->asHtml()->onlyOnIndex(),
            Text::make('文章标题', 'title')->hideFromIndex()->hideWhenCreating(),
            Text::make('评论数', function () {
                return $this->comments()->count();
            })->hideWhenCreating(),
            Text::make('点赞数', 'count_likes')->hideWhenCreating(),
            Text::make('用户支付的奖金', function () {
                return $this->issue ? $this->issue->gold : "";
            }),
            Textarea::make('文章内容', 'body')->rules('required')->hideFromIndex(),
            Select::make('状态', 'status')->options([
                1  => '公开',
                0  => '草稿',
                -1 => '下架',
            ])->displayUsingLabels(),

            Select::make('类型', 'type')->options([
                'post'  => '动态',
                'issue' => '问答',
            ])->displayUsingLabels(),

            Select::make('审核', 'submit')->options(\App\Article::getSubmitStatus())->displayUsingLabels(),

            BelongsTo::make('作者', 'user', 'App\Nova\User')->exceptOnForms(),
            BelongsTo::make('分类', 'category', 'App\Nova\Category')->withMeta([
                'belongsToId' => 1, //$this->NovaDefaultCategory(), //指定默认分类
            ]),
            Text::make('时间', function () {
                return time_ago($this->created_at);
            })->onlyOnIndex(),
            // Number::make('总评论数', 'count_comments')->exceptOnForms()->sortable(),
            File::make('上传视频', 'video_id')->onlyOnForms()->store(
                function (Request $request, $model) {
                    $file = $request->file('video_id');
                    $validator = Validator::make($request->all(), [
                        'video' => 'mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime',
                    ]);
                    if ($validator->fails()) {
                        return '视频格式有问题';
                    }
                    return $model->saveVideoFile($file);
                }
            ),
            BelongsTo::make('视频', 'video', Video::class)->exceptOnForms(),
            ArrayImages::make('图片', function () {
                return $this->screenshots;
            }),
            // BelongsTo::make('问题', 'issue', Issue::class),
            Text::make('视频时长', function () {
                if ($video = $this->video) {
                    return $video->duration;
                }
            }),
            Text::make('备注', 'remark')->onlyOnDetail(),
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
        return [
            new Filters\Article\ArticleType,
            new Filters\Article\ArticleStatusType,
            new ArticleSource,
            new ArticleSubmitFilter,
        ];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [
            new \App\Nova\Actions\Article\UpdateArticle,
            new AuditVideoPostSubmitStatus,
        ];
    }
}

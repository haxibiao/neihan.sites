<?php

namespace App\Nova;


use Haxibiao\Question\Audit;
use Haxibiao\Question\Question as QuestionQuestion;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Question extends Resource
{
    public static $model = 'Haxibiao\Question\Question';
    public static $title = 'description';

    public static $search = [
        'user_id', 'description',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        //FIXME:没有QuestionFormScope
        // return $query->withoutGlobalScope(QuestionFormScope::class);
    }

    public static $category = "题库管理";

    public static function label()
    {
        return '题目';
    }

    public static function singluarLable()
    {
        return '题目';
    }

    public static $perPageViaRelationship = 10;
    public static $parent                 = null;
    public static $with                   = ['user', 'category', 'audits', 'comments', 'likes', 'explanation'];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('审核id', 'review_id')->onlyOnDetail(),
            Text::make('描述', function () {
                return sprintf(
                    '<a href="%s" class="no-underline dim text-primary font-bold"> %s </a>',
                    nova_resource_uri($this),
                    str_limit($this->description, 50)
                );
            })->onlyOnIndex()->asHtml(),
            Text::make('描述', 'description')->rules('required', 'max:1000')->hideFromIndex(),
            Code::make('选项', 'selections')->json(JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE)->rules('required', 'max:4000'),
            Text::make('答案', 'answer')->rules('required', 'max:10')->hideFromIndex(),
            Number::make('智慧', 'gold')->min(0)->help('请勿调整精力点>=6')->hideFromIndex(),
            Number::make('精力', 'ticket')->min(0)->onlyOnDetail(),
            Image::make('配图', 'cover')->disk('cos')->hideFromIndex()
                ->preview(function ($value, $disk) {
                    return $value ?? null;
                })
                ->store(function (Request $request, $model) {
                    return $model->saveImage($request->file('cover'));
                }),
            //关联关系
            // BelongsTo::make('分类', 'category', Category::class)->nullable(),

            Select::make('类型', 'type')->options(QuestionQuestion::getTypes())->displayUsingLabels(),
            // Select::make('题目形式', 'form')->options($this::getForms())->displayUsingLabels(),

            Number::make('对', 'correct_count')->sortable()->min(0)->exceptOnForms(),
            Number::make('错', 'wrong_count')->sortable()->min(0)->exceptOnForms(),
            BelongsTo::make('用户', 'user', 'App\Nova\User')->nullable()->exceptOnForms(),
            BelongsTo::make('视频', 'video', 'App\Nova\Video')->nullable()->hideFromIndex(),
            BelongsTo::make('音频', 'audio', 'App\Nova\Audio')->hideFromIndex(),
            Select::make('审核', 'submit')->options(QuestionQuestion::getSubmitStatus())->hideFromIndex(),

            DateTime::make('时间', 'created_at')->hideFromIndex()->exceptOnForms(),
            Text::make('权', 'rank')->sortable()->help('权重1-4'),
            Text::make('备注', 'remark')->hideFromIndex(),
            Number::make('赞成', 'accepted_count')->min(0)->exceptOnForms()->sortable()->hideFromIndex(),
            Number::make('反对', 'declined_count')->min(0)->exceptOnForms()->sortable()->hideFromIndex(),
            Text::make('赞', 'count_likes')->exceptOnForms()->sortable(),
            Text::make('评', 'count_comments')->exceptOnForms()->sortable(),
            MorphMany::make('评论', 'comments', 'App\Nova\Comment'),
            Text::make('视频播放地址', function () {
                if (empty($this->video)) {
                    return null;
                }
                $url = $this->video->url;

                return sprintf(
                    '<a href="%s" class="no-underline dim text-primary font-bold" target="_blank"> %s </a>',
                    $url,
                    '点此播放'
                );
            })->hideFromIndex()->asHtml(),
            HasMany::make('审题', 'audits', 'App\Nova\Audit')->hideFromIndex(),
            MorphMany::make('点赞', 'likes', 'App\Nova\Like'),
            MorphMany::make('举报', 'reports', 'App\Nova\Report'),
            // HasMany::make('答题记录', 'answer_logs', Answer::class)->hideFromIndex(),
            BelongsTo::make('解析', 'explanation', 'App\Nova\Explanation')->hideFromIndex(),
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
            new \Haxibiao\Question\Nova\Metrics\Question\ReportQuestionsPerDay,
            new \Haxibiao\Question\Nova\Metrics\Question\AuditQuestionPerDay,
            new \Haxibiao\Question\Nova\Metrics\Question\RefusedQuestionsPerDay,
            new \Haxibiao\Question\Nova\Metrics\Curation\CurationPerDay,
            new \Haxibiao\Question\Nova\Metrics\Question\QuestionPerDay,
            new \Haxibiao\Question\Nova\Metrics\Question\AuditQuestionPerDay,
            new \Haxibiao\Question\Nova\Metrics\Question\RefusedQuestionsPerDay,
            new \Haxibiao\Question\Nova\Metrics\Question\ReportQuestionsPerDay,
            new \Haxibiao\Question\Nova\Metrics\Explanation\ExplantionToday,
            new \Haxibiao\Question\Nova\Metrics\Question\NewQuestionTypeToday,
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
            new \Haxibiao\Question\Nova\Filters\Question\QuestionSubmitStatus,
            new \Haxibiao\Question\Nova\Filters\Question\QuestionTypeFilter,
            new \Haxibiao\Question\Nova\Filters\Question\QuestionExplanationFilter,
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
            new \Haxibiao\Question\Nova\Actions\Question\AuditQuestionSubmitStatus,
            new \Haxibiao\Question\Nova\Actions\Question\UpdateCategory,
            new \Haxibiao\Question\Nova\Actions\Question\QuestionRank,
            new \Haxibiao\Question\Nova\Actions\Question\SetGoldAndTicket,
            new \Haxibiao\Question\Nova\Actions\Question\RecommendQuestion,
        ];
    }
}

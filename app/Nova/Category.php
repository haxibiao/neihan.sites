<?php

namespace App\Nova;

use Haxibiao\Question\Category as QuestionCategory;
use Haxibiao\Question\Question;
use Haxibiao\Question\Tag;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Haxibiao\Question\Category';

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
        'name',
    ];

    public static function label()
    {
        return '分类';
    }

    public static function singularLabel()
    {
        return '分类';
    }

    public static $category = "题库管理";

    /**
     * 预加载关联关系
     * @var array
     */
    public static $with = ['parent', 'user'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $rank = empty($this->rank) ? '0' : $this->rank;
        return [
            ID::make()->sortable(),
            Text::make('名称', 'name')->hideFromIndex(),
            Text::make('名称', function () {
                return sprintf(
                    '<a href="%s" class="no-underline dim text-primary font-bold"> %s </a>',
                    nova_resource_uri($this),
                    str_limit($this->name, 10)
                );
            })->onlyOnIndex()->asHtml(),
            Textarea::make('描述', 'description'),
            Textarea::make('tips', 'tips'),
            Image::make('图标', 'icon')->disk('local')->store(function (Request $request, $model) {
                return $model->saveIcon($request->file('icon'))->icon;
            })->thumbnail(function () {
                return $this->icon_url;
            })->hideWhenCreating(),
            BelongsTo::make('上级分类', 'parent', 'App\Nova\Category')->exceptOnForms(),
            MorphToMany::make('标签', 'tags', Tag::class)->exceptOnForms(),
            Number::make('上级分类ID', 'parent_id')->onlyOnForms(),
            Select::make('状态', 'status')->options(QuestionCategory::getStatuses())->displayUsingLabels(),
            Select::make('允许出题', 'allow_submit')->options(QuestionCategory::getAllowSubmits())->displayUsingLabels(),
            Select::make('官方', 'is_official')->options([
                '0' => '否',
                '1' => '是',
            ])->displayUsingLabels(),
            Text::make('题目数', function () {
                return $this->questions_count;
            }),
            Text::make('答题数', function () {
                return $this->answers_count;
            }),
            //            Text::make('可审题人数', function () {
            //                return $this->can_review_count;
            //            }),
            Number::make('出题最小答对数', 'min_answer_correct')->hideFromIndex(),
            Number::make('排名', 'rank')->help('数字越大,排名越靠前(建议范围0-999)')->withMeta(['value' => $rank]),
            BelongsTo::make('用户', 'user', 'App\Nova\User')->exceptOnForms(),
            HasMany::make('题目列表', 'questions', 'App\Nova\Question')->singularLabel('题目'),
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
            new \Haxibiao\Question\Nova\Filters\Category\CategoryTagFilter,
            new \Haxibiao\Question\Nova\Filters\Category\CategoryQuestionsCountOrder,
            new \Haxibiao\Question\Nova\Filters\Category\CategoryAnswersCountOrder,
            new \Haxibiao\Question\Nova\Filters\Category\CategoryStatusFilter,
            new \Haxibiao\Question\Nova\Filters\Category\CategorySubmitFilter,
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
            new \Haxibiao\Question\Nova\Actions\Category\UpdateTag,
        ];
    }
}

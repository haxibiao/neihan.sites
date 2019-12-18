<?php

namespace App\Nova;

use App\Nova\Actions\UpdateUser;
use App\User as AppUser;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\User';

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
        'id', 'name', 'email', 'account', 'phone',
    ];

    public static function label()
    {
        return "用户";
    }

    public static $group = '用户管理';

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

            Text::make('名字', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('性别', 'gender')->options([
                AppUser::MALE_GENDER   => '男',
                AppUser::FEMALE_GENDER => '女',
            ])->displayUsingLabels(),

            Text::make('年龄', 'age'),

            Text::make('发布内容数', function () {
                return $this->allArticles()->count();
            }),

            hasMany::make('钱包', 'wallets', Wallet::class),

            Number::make('智慧点', 'gold')->exceptOnForms(),
            Text::make('账户', 'account'),
            Text::make('手机号', 'phone'),
            Text::make('邮件地址', 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            DateTime::make('创建时间', 'created_at'),
            DateTime::make('最后登录时间', 'updated_at'),

            Select::make('状态', 'status')->options([
                AppUser::STATUS_ONLINE  => '上线',
                AppUser::STATUS_OFFLINE => '下线',
            ])->displayUsingLabels(),

            HasMany::make('智慧点明细', 'golds', Gold::class)->onlyOnDetail(),
            HasMany::make('贡献记录', 'contributes', Contribute::class),
            HasMany::make('用户文章', 'videoArticles', Article::class),

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
            new \App\Nova\Filters\User\UserStatusType,
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
            new UpdateUser,
        ];
    }
}

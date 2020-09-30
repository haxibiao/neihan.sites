<?php

namespace App\Nova;

use App\User as AppUser;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Password;
use App\Nova\Filters\User\UserRoleID;

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

    public static $with = ['wallets', 'golds', 'contributes', 'videoArticles', 'posts'];
    public static function label()
    {
        return "用户";
    }
    public static function singularLabel()
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
            Image::make('头像', 'avatar')
                ->store(function (Request $request, $model) {
                    $file = $request->file('avatar');
                    return $model->saveDownloadImage($file);
                })
                ->thumbnail(function () {
                    return $this->avatar;
                })->preview(function () {
                    return $this->avatar;
                })->disableDownload(),
            Text::make('最近使用版本', 'profile.app_version')->sortable()->onlyOnDetail(),
            Select::make('性别', 'gender')->options([
                AppUser::MALE_GENDER   => '男',
                AppUser::FEMALE_GENDER => '女',
            ])->displayUsingLabels()->onlyOnDetail(),

            Select::make('权限', 'role_id')->options([
                AppUser::USER_STATUS    => '平民玩家',
                AppUser::EDITOR_STATUS  => '运营人员',
                AppUser::VEST_STATUS    => '马甲号',
            ])->displayUsingLabels()->onlyOnForms(),

            Text::make('年龄', 'age')->onlyOnDetail(),
            HasMany::make('钱包', 'wallets', Wallet::class)->onlyOnDetail(),

            // Text::make('发布内容数', function () {
            //     return $this->posts()->count();
            // })->hideWhenUpdating(),
            // Number::make('智慧点', 'gold')->exceptOnForms()->hideWhenUpdating(),


            Text::make('账户', 'account')->onlyOnDetail(),
            Text::make('uuid', 'uuid')->onlyOnDetail(),
            Text::make('api_token', 'api_token')->hideFromIndex()->onlyOnDetail(),
            Text::make('手机号', 'phone'),
            Text::make('邮件地址', 'email'),
            Password::make('密码', 'Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),

            DateTime::make('创建时间', 'created_at')->onlyOnDetail(),
            DateTime::make('最后登录时间', 'updated_at')->onlyOnDetail(),

            Select::make('状态', 'status')->options([
                AppUser::STATUS_ONLINE  => '上线',
                AppUser::STATUS_OFFLINE => '下线',
                AppUser::STATUS_FREEZE  => '状态异常系统封禁',
            ])->displayUsingLabels()->onlyOnDetail(),

            HasMany::make('智慧点明细', 'golds', Gold::class)->onlyOnDetail(),
            HasMany::make('贡献记录', 'contributes', Contribute::class)->onlyOnDetail(),
            HasMany::make('用户文章', 'videoArticles', Article::class)->onlyOnDetail(),
            HasMany::make('用户动态', 'posts', Post::class)->onlyOnDetail(),

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
            // new \App\Nova\Filters\User\UserStatusType,
            // new UserRoleID,
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
            // new UpdateUser,
            // new BindDongdezhuanAccount,
        ];
    }
}

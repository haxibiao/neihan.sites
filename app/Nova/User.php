<?php

namespace App\Nova;

use App\Nova\Actions\BindDongdezhuanAccount;
use App\Nova\Actions\UpdateUser;
use App\Nova\Filters\User\UserRoleID;
use App\User as AppUser;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
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
            Image::make('头像', 'avatar')
                ->store(function (Request $request, $model) {
                    $file = $request->file('avatar');
                    return $model->saveDownloadImage($file);
                })
                ->thumbnail(function () {
                    return $this->avatar_url;
                })->preview(function () {
                    return $this->avatar_url;
                })->disableDownload(),
            Text::make('最近使用版本', 'profile.app_version')->sortable()->hideWhenUpdating(),
            Select::make('性别', 'gender')->options([
                AppUser::MALE_GENDER   => '男',
                AppUser::FEMALE_GENDER => '女',
            ])->displayUsingLabels()->hideWhenUpdating(),

            Select::make('权限', 'role_id')->options([
                AppUser::USER_STATUS    => '平民玩家',
                AppUser::EDITOR_STATUS  => '运营人员',
                AppUser::VEST_STATUS    => '马甲号',
            ])->displayUsingLabels()->onlyOnForms(),

            Text::make('年龄', 'age')->hideWhenUpdating(),

            Text::make('发布内容数', function () {
                return $this->posts()->count();
            })->hideWhenUpdating(),

            hasMany::make('钱包', 'wallets', Wallet::class)->hideWhenUpdating(),

            Number::make('智慧点', 'gold')->exceptOnForms()->hideWhenUpdating(),
            Text::make('账户', 'account'),
            Text::make('uuid', 'uuid')->hideFromIndex(),
            Text::make('api_token', 'api_token')->hideFromIndex()->hideWhenUpdating(),
            Text::make('手机号', 'phone'),
            Text::make('邮件地址', 'email'),

            DateTime::make('创建时间', 'created_at')->hideWhenUpdating(),
            DateTime::make('最后登录时间', 'updated_at')->hideWhenUpdating(),

            Select::make('状态', 'status')->options([
                AppUser::STATUS_ONLINE  => '上线',
                AppUser::STATUS_OFFLINE => '下线',
                AppUser::STATUS_FREEZE  => '状态异常系统封禁',
            ])->displayUsingLabels(),

            HasMany::make('智慧点明细', 'golds', Gold::class)->onlyOnDetail(),
            HasMany::make('贡献记录', 'contributes', Contribute::class),
            HasMany::make('用户文章', 'videoArticles', Article::class),
            HasMany::make('用户动态','posts',Post::class),

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
            new UserRoleID,
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
            new BindDongdezhuanAccount,
        ];
    }
}

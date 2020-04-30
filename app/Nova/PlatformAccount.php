<?php

namespace App\Nova;

use App\Nova\Filters\PlatformAccountStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class PlatformAccount extends Resource
{

    public static $model = 'App\\PlatformAccount';

    public static $title = 'title';

    public static $group = '电商系统';

    public static $search = [
        'id', 'account',
    ];

    public static function label()
    {
        return "游戏账号";
    }

    public static function singularLabel()
    {
        return "游戏账号";
    }

    public function fields(Request $request)
    {
        $user = getUser();
        return [
            ID::make()->sortable(),
            // BelongsTo::make("使用用户", "user", User::class)->onlyOnIndex(),
            // BelongsTo::make("历史订单", "order", Order::class)->onlyOnIndex(),
            // BelongsTo::make("所属商品", "product", Product::class)->onlyOnIndex(),
            Text::make('账号来源', "platform"),
            Text::make('账号', 'account'),
            Text::make('价格', 'price'),
            Text::make('规格1(角色皮肤)', 'dimension'),
            Text::make('规格2(数字时长)', 'dimension2'),
            Text::make('密码', 'password')->onlyOnForms(),
            Select::make('状态', 'order_status')->options([
                0 => '未使用',
                1 => '使用中',
                2 => '已到期',
                -1 => '不可用',
            ])->displayUsingLabels(),
        ];
    }

    public function cards(Request $request)
    {
        return [
        ];
    }

    public function filters(Request $request)
    {
        return [
            new PlatformAccountStatus,
        ];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [

        ];
    }
}

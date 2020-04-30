<?php

namespace App\Nova;

use App\Nova\Product;
use App\Nova\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Order extends Resource
{

    public static $model = 'App\\Order';

    public static $title = 'title';

    public static $group = '电商系统';

    public static $search = [
        'id', 'number', 'user_id',
    ];

    public static function label()
    {
        return "商品订单";
    }

    public static function singularLabel()
    {
        return "商品订单";
    }

    public function fields(Request $request)
    {
        $user = getUser();
        return [
            ID::make()->sortable(),
            BelongsTo::make("租借用户", "user", User::class)->onlyOnIndex(),
            HasMany::make("租借账号", "platformAccount", PlatformAccount::class),
            BelongsToMany::make("租借商品", "products", Product::class)->onlyOnIndex(),
            Text::make('订单号', 'number'),
            DateTime::make('创建时间', 'created_at'),
            Select::make('状态', 'status')->options([
                0 => '未支付',
                1 => '已支付',
                2 => '已到货',
                3 => '已到期',
            ])->displayUsingLabels()->onlyOnIndex(),
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

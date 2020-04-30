<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Store extends Resource
{

    public static $model = 'App\\Store';

    public static $title = 'title';

    public static $group = '电商系统';

    public static $search = [
        'id', 'name',
    ];

    public static function label()
    {
        return "商铺";
    }

    public static function singularLabel()
    {
        return "商铺";
    }

    public function fields(Request $request)
    {
        $user = getUser();
        return [
            ID::make()->sortable(),
            Text::make('商铺名称', "name")->rules('required'),
            // Text::make('用户', function () {
            //     return getUser()->name;
            // }),
            Select::make('店铺老板', 'user_id')->rules('required')->options(
                \App\User::where("id", is_null(getUser()->store) ? getUser()->id : -1)->pluck("name", "id")
            )->displayUsingLabels(),
            HasMany::make("店铺商品", "product", Product::class),
            Text::make('商铺介绍', 'description')->rules('required'),
            HasMany::make('上传图片', 'image', Image::class),
            Select::make('状态', 'status')->rules('required')->options([
                1 => '上架',
                -1 => '下架',
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

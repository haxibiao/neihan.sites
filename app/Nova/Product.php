<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;

class Product extends Resource
{

    public static $model = 'App\\Product';

    public static $title = 'title';

    public static $group = '电商系统';

    public static $search = [
        'id', 'name',
    ];

    public static function label()
    {
        return "商品";
    }

    public static function singularLabel()
    {
        return "商品";
    }

    public function fields(Request $request)
    {
        $user = getUser();
        return [
            ID::make()->sortable(),
            Text::make('商品名称', "name")->rules('required'),
            Text::make('商品描述', 'description')->rules('required')->hideFromIndex(),
            Select::make('状态', 'status')->rules('required')->options([
                1 => '上架',
                -1 => '下架',
            ])->displayUsingLabels(),
            Select::make('所属商铺', 'store_id')->rules('required')->options(
                \App\Store::where("user_id", getUser()->id)->pluck("name", "id")
            )->displayUsingLabels(),

            Select::make('分类', 'category_id')->rules('required')->options(
                \App\Category::where('type', 'product')->pluck('name', 'id')
            )->displayUsingLabels(),

            HasMany::make("游戏账号", "platformAccount", PlatformAccount::class),
            Text::make('商品价格', 'price'),
            Text::make('上架数量', 'available_amount')->onlyOnIndex(),
            Text::make('商品规格', 'dimension'),

            HasMany::make('上传图片', 'image', Image::class),

            File::make('上传视频', 'video_id')->rules('required')->hideWhenUpdating()->hideFromDetail()->store(
                function (Request $request, $model) {
                    $file = $request->file('video_id');
                    $validator = Validator::make($request->all(), [
                        'video' => 'mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime',
                    ]);
                    if ($validator->fails()) {
                        return '视频格式有问题';
                    }
                    return $model->saveVideoFile($file);
                }),
            File::make('上传视频', 'video_id')->hideWhenCreating()->store(
                function (Request $request, $model) {
                    $file = $request->file('video_id');
                    $validator = Validator::make($request->all(), [
                        'video' => 'mimetypes:video/avi,video/mp4,video/mpeg,video/quicktime',
                    ]);
                    if ($validator->fails()) {
                        return '视频格式有问题';
                    }
                    return $model->saveVideoFile($file);
                }),

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

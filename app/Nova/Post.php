<?php

namespace App\Nova;

use App\Nova\Actions\PickCollectionPost;
use App\Nova\Filters\PostAuthor;
use App\Nova\Filters\PostOwner;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;

class Post extends Resource
{
    //public static $title = 'description';

    public static $model = 'App\\Post';

    public static $group = '内容管理';

    public static $search = [
        'id','content'
    ];

    public static function label()
    {
        return "动态";
    }

    public static function singularLabel()
    {
        return "动态";
    }

    public function fields(Request $request)
    {
        $user = getUser();
        return [
            ID::make()->sortable(),
            Textarea::make('文章内容', 'content')->rules('required')->showOnIndex(),
            BelongsTo::make('作者', 'user', 'App\Nova\User')->exceptOnForms(),
            BelongsTo::make('真实作者', 'owner', 'App\Nova\User')->exceptOnForms(),
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
            new PostAuthor,
            new PostOwner,
        ];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [
            new PickCollectionPost,
        ];
    }
}

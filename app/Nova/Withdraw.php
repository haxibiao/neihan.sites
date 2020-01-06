<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Withdraw extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Withdraw';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return "提现";
    }

    public static $group = '交易管理';

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
            Text::make('钱包ID','wallet_id'),
            Select::make('状态', 'status')->options([
                1  => '提现成功',
                0  => '待处理',
                -1 => '提现失败',
            ])->displayUsingLabels(),

            Text::make('金额','amount'),
            Text::make('提现账号',function(){
                return $this->wallet->pay_account;
            }),
            Text::make('真实姓名',function(){
                return $this->wallet->real_name;
            }),
            Text::make('支付平台','to_platform'),
            

            Text::make('备注', 'remark'),
            DateTime::make('创建时间', 'created_at'),
            DateTime::make('更新时间', 'updated_at'),
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
            (new \Hxb\CategoryCount\CategoryCount)
                ->withName("提现排行前十个用户统计")
                ->withLegend("提现金额")
                ->withColor("#E6E61A")
                ->withData(\App\User::getTopWithDraw(10)),
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
            new  \App\Nova\Filters\Transaction\WithDrawStatusType,
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
        return [];
    }
}

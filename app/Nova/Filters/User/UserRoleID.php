<?php

namespace App\Nova\Filters\User;

use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class UserRoleID extends Filter
{
    public $name = '用户身份';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('role_id',$value);;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            '普通用户'  =>  User::USER_STATUS,
            '运营用户'  =>  User::EDITOR_STATUS,
            '管理员'    =>  User::ADMIN_STATUS,
            '马甲用户'  =>  User::VEST_STATUS,
        ];
    }
}

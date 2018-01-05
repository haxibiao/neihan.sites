@extends('layouts.app')

@section('title')
    我的钱包 - 爱你城
@stop
@section('content')
<div id="wallet">
    <div class="container">
        <div class="row">
            <div class="top clearfix">
                <div class="user col-xs-12 col-sm-4">
                    <a class="avatar avatar_lg" href="/user/{{ $user->id }}">
                        <img src="{{ $user->avatar }}"/>
                    </a>
                    <div class="info">
                        <a class="name" href="/user/{{ $user->id }}">
                            {{ $user->name }}
                        </a>
                        <div class="mobile_bind">
                            已绑定手机
                        </div>
                    </div>
                </div>

                <div class="middle col-xs-12 col-sm-5">
                    <div class="account_money">
                        账户余额
                    </div>
                    <span class="money_main">
                        {{ floor($user->balance()) }}
                    </span>
                    <span class="money_sub">
                        @php
                           $weishu=($user->balance() -floor($user->balance())) *100;
                        @endphp
                        .{{ $weishu <10?'0'.$weishu:$weishu }}元
                    </span>
                    <div class="action">
{{--                         <a class="btn_base btn_follow btn_followed_sm recharge" href="/alipay/wap/pay?amount={{ rand(1,5)/100 }}">
                            充值
                        </a> --}}
{{--                         <div class="btn_base btn_hollow btn_followed_sm withdrawals">
                            提现
                        </div> --}}
                        <div class="btn_base btn_follow btn_followed_sm" data-target="#rechargeModal" data-toggle="modal">
                            充值
                        </div>
                        <div class="btn_base btn_hollow btn_followed_sm disable" data-target="#withdrawModal" data-toggle="modal">
                            提现
                        </div>
                        <span class="warn">
                            *当前余额不足
                        </span>
                    </div>
                </div>
                <div class="meta col-xs-12 col-sm-3">
                    <div>每次提现最小额度为￥100.00</div>
                    <div>每次提现收取 5% 手续费</div>
                    <div>提现会在 3-5 个工作日内到账</div>
                    <a href="javascript:;" class="help" data-target="#periodModal" data-toggle="modal">提现遇到问题?</a>
                    <a href="javascript:;" class="help" data-target="#whyModal" data-toggle="modal">提现手续费是怎么收取的?</a>
                        <recharge-modal></recharge-modal>
                        <withdraw-modal></withdraw-modal>
                        <period-modal></period-modal>
                        <why-modal></why-modal>
                </div>
            </div>
            <ul class="body">
                <li class="title">
                    <div class="time">
                        时间
                    </div>
                    <div class="type">
                        类型
                    </div>
                    <div class="details">
                        详情
                    </div>
                    <div class="amount">
                        金额
                    </div>
                    <div class="remark">
                        状态
                    </div>
                </li>
                @foreach($transactions as $transaction)
                <li class="note">
                    <div class="time">
                        {{ $transaction->created_at }}
                    </div>
                    <div class="type">
                        {{ $transaction->type }}
                    </div>
                    <div class="details">
                        {!! $transaction->log !!}
                    </div>
                    <div class="amount reduce">
                        ￥{{ $transaction->amount }}
                    </div>
                    <div class="remark">
                      {{ $transaction->status }}
                    </div>
                </li>
                @endforeach
            </ul>

            <div class="pager">
                {!! $transactions->links() !!}
            </div>
        </div>
    </div>
</div>
@stop

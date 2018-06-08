@extends('layouts.app')
@section('title')
    我的钱包 - {{ env('APP_NAME') }}
@stop　
@section('content')
<div id="wallet">
    <div class="wallet-top clearfix">
        <div class="col-sm-3 left">
            <div class="note-info info-lg note-info-wallet">    
                <a class="avatar" href="/user/{{ $user->id }}">
                    <img alt="" src="{{ $user->avatar() }}"/>
                </a>       
                <div class="title">
                    <a class="name" href="/user/{{ $user->id }}">
                        {{ $user->name }}
                    </a>
                </div>
                <div class="info">
                    已绑定手机
                </div>
              </div>
        </div>
        <div class="col-sm-5 middle">
            <div class="wallet-account">
                <span class="account-money">
                    账户余额
                </span>
                <span class="main-money">
                    {{ floor($user->balance()) }}
                </span>
                <span class="money-sub">
                    @php
                		$weishu = floor(($user->balance() - floor($user->balance())) * 100);
                	@endphp
                    .{{ $weishu < 10 ? '0'.$weishu : $weishu }}元
                </span>
                <div class="action">
                  <a class="btn-base btn-handle btn-md" data-target=".modal-to-up" data-toggle="modal">充值</a>
                  <modal-to-up></modal-to-up>
                  <a class="btn-base btn-hollow btn-md " data-target=".modal-withdraw" data-toggle="modal">
                    提现
                  </a>
                  <modal-withdraw></modal-withdraw>
                  {{-- <span class="warn">*当前余额不足</span> 低于100元时显示，并且在提现按钮上添加disable类--}}
                </div>
            </div>
        </div>
        <div class="col-sm-4 right">
            <div class="wallet-meta">
                <div class="notice">每次提现最小额度为￥100.00</div>
                <div class="notice">每次提现收取 5% 手续费</div>
                <div class="notice">提现会在 3-5 个工作日内到账</div>
                <a class="help" data-target=".period" data-toggle="modal">提现遇到问题?</a>
                <a class="help" data-target=".why" data-toggle="modal">提现手续费是怎么收取的?</a>
                @include('modals.period')
                @include('modals.why')
            </div>
        </div>
    </div>
    <ul class="wallet-history">
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
        <li>
            <div class="time">
                {{ $transaction->created_at }}
            </div>
            <div class="type">
                {{ $transaction->type }}
            </div>
            <div class="details">
                {!! $transaction->log !!}
            </div>
            <div class="amount plus">
                ￥{{ $transaction->amount }}
            </div>
            <div class="remark">
                <div class="state">
                    {{ $transaction->status }}
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    <div class="pager">
        {!! $transactions->links() !!}
    </div>
     
</div>
@stop

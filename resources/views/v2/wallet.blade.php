@extends('v2.layouts.app')

@section('title')
    我的钱包 - 爱你城
@stop
@section('content')
<div id="wallet">
    <div class="container">
        <div class="row">
            <div class="top clearfix">
                <div class="user col-xs-12 col-sm-4">
                    <a class="avatar avatar_lg" href="/v2/home">
                        <img src="/images/photo_01.jpg"/>
                    </a>
                    <div class="info">
                        <a class="name" href="/v2/home">
                            喵星菇凉
                        </a>
                        <div class="mobile_bind">
                            未绑定手机（
                            <a href="javascript:;">
                                去绑定
                            </a>
                            ）
                        </div>
                    </div>
                </div>
                <div class="middle col-xs-12 col-sm-5">
                    <div class="account_money">
                        账户余额
                    </div>
                    <span class="money_main">
                        0
                    </span>
                    <span class="money_sub">
                        .00元
                    </span>
                    <div class="action">
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
                </div>
                <recharge-modal></recharge-modal>
                <withdraw-modal></withdraw-modal>
                <period-modal></period-modal>
                <why-modal></why-modal>
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
                <li class="note">
                    <div class="time">
                        2017-09-13 21:48
                    </div>
                    <div class="type">
                        赞赏
                    </div>
                    <div class="details">
                        向
                        <a href="/v2/user">
                            小万PPT
                        </a>
                        的文章
                        <a href="/v2/detail" target="_blank">
                            《99分的简历是什么样的？》
                        </a>
                        送了2颗糖
                    </div>
                    <div class="amount reduce">
                        -￥2.00
                    </div>
                    <div class="remark">
                        未支付
                    </div>
                </li>
                <li class="note">
                    <div class="time">
                        2017-09-12 01:48
                    </div>
                    <div class="type">
                        赞赏
                    </div>
                    <div class="details">
                        向
                        <a href="/v2/user">
                            小万PPT
                        </a>
                        的文章
                        <a href="/v2/detail" target="_blank">
                            《99分的简历是什么样的？》
                        </a>
                        送了20颗糖
                    </div>
                    <div class="amount reduce">
                        -￥20.00
                    </div>
                    <div class="remark">
                        未支付
                    </div>
                </li>
                <li class="note">
                    <div class="time">
                        2017-08-11 15:30
                    </div>
                    <div class="type">
                        收到赞赏
                    </div>
                    <div class="details">
                        <a href="/v2/user">
                            中南工大留级生
                        </a>
                        向你的文章
                        <a href="/v2/detail" target="_blank">
                            《在Web项目中添加自定义的font图标》
                        </a>
                        送了9899999颗糖
                    </div>
                    <div class="amount plus">
                        +￥9899999.00
                    </div>
                    <div class="remark">
                        已到账
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@stop

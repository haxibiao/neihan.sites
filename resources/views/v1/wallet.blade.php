@extends('v1.layouts.blank')

@section('title')
    我的钱包 - 爱你城
@stop
@section('content')
<div id="wallet">
    <div class="container">
        <div class="row">
            <div class="top clearfix">
                <div class="user col-xs-12 col-sm-4">
                    <a class="avatar" href="#">
                        <img src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                    </a>
                    <div class="info">
                        <a class="name" href="#">
                            喵星菇凉
                        </a>
                        <div class="mobile_bind">
                            未绑定手机（
                            <a href="#">
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
                        <div class="recharge">
                            充值
                        </div>
                        <div class="withdrawals">
                            提现
                        </div>
                        <span class="warn">
                            *当前余额不足
                        </span>
                    </div>
                </div>
                <div class="meta col-xs-12 col-sm-3">
                    <div>
                        每次提现最小额度为￥100.00
                    </div>
                    <div>
                        每次提现收取 5% 手续费
                    </div>
                    <div>
                        提现会在 3-5 个工作日内到账
                    </div>
                    <a href="#">
                        提现遇到问题?
                    </a>
                    <a href="#">
                        提现手续费是怎么收取的?
                    </a>
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
                <li class="note">
                    <div class="time">
                        2017-09-13 21:48
                    </div>
                    <div class="type">
                        赞赏
                    </div>
                    <div class="details">
                        向
                        <a href="#">
                            小万PPT
                        </a>
                        的文章
                        <a href="#">
                            《99分的简历是什么样的？》
                        </a>
                        送了2颗糖
                    </div>
                    <div class="amount">
                        -￥2.00
                    </div>
                    <div class="remark">
                        未支付
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
                        <a href="#">
                            小万PPT
                        </a>
                        的文章
                        <a href="#">
                            《99分的简历是什么样的？》
                        </a>
                        送了2颗糖
                    </div>
                    <div class="amount">
                        -￥2.00
                    </div>
                    <div class="remark">
                        未支付
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@stop

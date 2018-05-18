@extends('v1.layouts.blank')

@section('title')
    注册 - 爱你城
@stop
@section('content')
<div id="sign_up">
    <div class="logo">
        <a href="/v1">
            <span class="love">
                爱
            </span>
            <span class="you">
                你
            </span>
            <span class="city">
                城
            </span>
        </a>
    </div>
    <div class="main">
        <h4 class="title">
            <div class="normal_title">
                <a href="/v1/sign_in">
                    登录
                </a>
                <b>
                    ·
                </b>
                <a class="active" href="javascript:;">
                    注册
                </a>
            </div>
        </h4>
        <div class="sign_in_contauner">
            <form accept-charset="utf-8" action="">
                <div class="input_prepend restyle">
                    <input placeholder="你的昵称" type="text"/>
                    <i class="iconfont icon-yonghu01">
                    </i>
                </div>
                <div class="input_prepend restyle no_radius">
                    <input placeholder="手机号" type="text"/>
                    <i class="iconfont icon-ordinarymobile">
                    </i>
                </div>
                <div class="input_prepend">
                    <input placeholder="设置密码" type="text"/>
                    <i class="iconfont icon-suo2">
                    </i>
                </div>
                <input class="btn_sign_up" type="submit" value="注册"/>
                <p class="sign_up_msg">
                    点击 “注册” 即表示您同意并愿意遵守爱你城
                    <br/>
                    <a href="#" target="_blank">
                        用户协议
                    </a>
                    和
                    <a href="#" target="_blank">
                        隐私政策
                    </a>
                </p>
            </form>
            <div class="more_sign">
                <h6>
                    社交账号直接注册
                </h6>
                <ul>
                    <li>
                        <a class="weibo" href="#" target="_blank">
                            <i class="iconfont icon-sina">
                            </i>
                        </a>
                    </li>
                    <li>
                        <a class="weixin" href="#" target="_blank">
                            <i class="iconfont icon-weixin1">
                            </i>
                        </a>
                    </li>
                    <li>
                        <a class="qq" href="#" target="_blank">
                            <i class="iconfont icon-qq2">
                            </i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

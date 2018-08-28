@extends('layouts.blank')

@section('content')
<div id="reset">
        <div class="logo"><a href="/"><img src="{{'/logo/'.get_domain().'.text.png' ?:'/logo/'.get_domain().'.png' }}" alt="{{ config('app.name') }}"></a></div>
        <div class="resetPasswordMain">
                <h4 class="reset-title">用邮箱重置密码</h4>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="{{ $errors->has('email') ? ' has-error' : '' }} input-prepend">                            
                                <input id="email" type="email"  name="email" value="{{ $email or old('email') }}" required autofocus placeholder="请输入注册或绑定的邮箱">
                                <i data-v-4bd21782="" class="iconfont icon-youjian"></i>
                                @if ($errors->has('email'))
                                    <span class="help-block email">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif                           
                        </div>
                        <div class="{{ $errors->has('password') ? ' has-error' : '' }} input-prepend">
                                <input id="password" type="password"  name="password" required placeholder="请输入新密码">
                                <i data-v-4bd21782="" class="iconfont icon-suo2"></i>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif   
                        </div>
                        <div class="{{ $errors->has('password_confirmation') ? ' has-error' : '' }} input-prepend ">    
                                <input id="password-confirm" type="password"  name="password_confirmation" required placeholder="请再输入一遍新密码" class="last-input">
                                <i data-v-4bd21782="" class="iconfont icon-suo2"></i>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif      
                        </div>
                            <button type="submit" class="btn-base btn-reset">
                                重置密码
                            </button>
                    </form>
                </div>
            
        </div>
</div>
@endsection

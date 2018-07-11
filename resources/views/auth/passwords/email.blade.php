@extends('layouts.blank')

@section('content')
<div id="email-reset">
    <div class="logo"><a href="/"><img src="{{'/logo/'.get_domain().'.text.png' ?:'/logo/'.get_domain().'.png' }}" alt="{{ config('app.name') }}"></a></div>
        <div class="reset-box">
                <h4 class="title">用邮箱重置密码</h4>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} input-prepend">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="请输入注册或绑定的邮箱">
                                <i data-v-4bd21782="" class="iconfont icon-youjian"></i>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            
                        </div>
                        
                                <button type="submit" class="btn-base submit-btn single-line">
                                    发送链接到邮箱
                                </button>
                           
                        
                    </form>
                </div>
        </div>

</div>
@endsection

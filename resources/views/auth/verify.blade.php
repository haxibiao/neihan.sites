@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('验证你的邮箱地址') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('新的验证链接已发送到您的电子邮件地址。') }}
                        </div>
                    @endif

                    {{ __('在继续之前，请确认你的邮箱为哈希表的邮箱') }}
                    {{ __('只有哈希表的邮箱才能接收邮件') }}, <a href="{{ route('verification.resend') }}">{{ __('点击此在次发送验证邮件') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
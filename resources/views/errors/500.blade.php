@extends('layouts.app')
@section('title')
    服务器错误 - {{ config('app.name_cn') }}
@endsection
@section('content')

    <div class="jumbotron" style="min-height: 400px">
        <div class="container error">
            <img src="/images/404.png" alt="">
            <div class="info">
                <h2>服务器内部错误,崩溃啦！</h2>
                <p class="state">错误信息已通过邮件发送给程序员，<a class="return" href="{{ url()->previous() }}"> 返回</a></p>
                <i class="iconfont icon-icon-test1"></i>
            </div>
        </div>
    </div>

@endsection

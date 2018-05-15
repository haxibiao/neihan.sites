@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron">
        <div class="container">
            <h1>
                权限不足
            </h1>
            <p class="lead">
                @if(session('message'))
                    {{ session('message') }}
                @endif
            </p>
            <p class="well">
                您的账户目前还没有权限访问本页，有问题可以咨询 ivan@haxibiao.com.
            </p>
            <p>
                <a class="btn btn-primary btn-lg" href="/">
                    返回首页
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

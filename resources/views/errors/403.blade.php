@extends('layouts.app')

@section('title')
    权限不足 - {{ seo_site_name() }}
@endsection

@section('content')

    <div class="jumbotron" style="min-height: 400px">
        <div class="container">
            <h1>
                权限不足
            </h1>
            <p class="lead">
                @if (session('message'))
                    {{ session('message') }}
                @endif
            </p>
            <p class="well">
                您的账户目前还没有权限访问本页，找管理员进行权限赋予，或者点击下方链接，已经进行邮箱验证来赋予权限.
            </p>
            <p>
                <a class="btn btn-primary btn-lg" href="/">
                    返回首页
                </a>
                <a class="btn btn-primary btn-lg" href="/email/verify">
                    邮箱验证
                </a>
            </p>
        </div>
    </div>

@endsection

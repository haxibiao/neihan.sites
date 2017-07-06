@extends('layouts.app')

@section('keywords')
     @foreach($data as $cate_name => $articles) {{ $cate_name }}, @endforeach
@stop

@section('description')
     {{ config('app.name', 'Laravel') }} 专注 @foreach($data as $cate_name => $articles) {{ $cate_name }},  @endforeach 方面的原创内容,专注分享,方便中国移动互联网网民学习交流.
@stop

@section('content')

<meta name="baidu-site-verification" content="1eDc5dWuPr"/>

<div class="container">
    <ol class="breadcrumb">
        <li class="active">
            首页
        </li>
    </ol>

    <div class="row">
        <div class="col-md-8">
            @include('parts.carousel')
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">                    
                    <h3 class="panel-title">本站新用户:</h3>
                </div>
                <div class="panel-body">                    
                    @foreach($users as $user)
                        <div class="col-xs-6 text-center">
                            <a href="/user/{{ $user->id }}" class="thumbnail">
                                <img src="{{ get_avatar($user) }}" alt="" class="img img-responsive">
                                <p>{{ $user->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row top20">
        @foreach($data as $cate_name => $articles)
        @if(!empty($articles))
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ $cate_name }}文章
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @foreach($articles as $article)
                        <a class="list-group-item" href="/article/{{ $article->id }}">
                            {{ $article->title }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

    </div>
</div>
@endsection

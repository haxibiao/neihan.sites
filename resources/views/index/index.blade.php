@extends('layouts.app')

@section('keywords')
     @foreach($data as $cate_name => $date_item) {{ $date_item['name'] }}, @endforeach
@stop

@section('description')
     {{ config('app.name', 'Laravel') }} 专注 @foreach($data as $cate_name => $date_item) {{ $date_item['name']}},  @endforeach 方面的原创内容,专注分享,方便中国移动互联网网民学习交流.
@stop

@section('content')

<div class="container">

    <div class="row">
        <div class="col-xs-4">
            <ol class="breadcrumb">
                <li class="">
                    首页
                </li>
            </ol>
        </div>
        <div class="col-xs-8">
            <form class="navbar-form navbar-left pull-right" action="/search" method="get">
              <div class="form-group pull-left right5">
                <input type="text" class="form-control" placeholder="搜索..." name="q">
              </div>
              <button type="submit" class="btn btn-default">搜索</button>
            </form>
        </div>
    </div>

    <div class="row hidden-xs hidden-sm">
        <div class="col-md-8">
            @include('parts.carousel')
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">                    
                    <h3 class="panel-title">热门</h3>
                </div>
                <div class="panel-body">                    
                    @foreach($hot_articles as $article)
                        @include('article.parts.article_item')
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row top20">
        @foreach($data as $cate_name_en => $data)
        @if(!$data['articles']->isEmpty())
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                <div class="pull-right">
                    <a href="/{{ $cate_name_en }}">更多</a>
                </div>
                    <h3 class="panel-title">
                        {{ $data['name'] }}
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @foreach($data['articles'] as $article)
                            @include('article.parts.article_item')
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

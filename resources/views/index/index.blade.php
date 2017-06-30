@extends('layouts.app')

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="active">
            首页
        </li>
    </ol>
    @include('parts.carousel')
    <div class="row" style="margin-top: 20px">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        中医文章
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @foreach($zhongyi_articles as $article)
                        <a class="list-group-item" href="/article/{{ $article->id }}">
                            {{ $article->title }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        西医文章
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @foreach($xiyi_articles as $article)
                        <a class="list-group-item" href="/article/{{ $article->id }}">
                            {{ $article->title }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

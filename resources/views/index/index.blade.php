@extends('layouts.app')

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="active">
            首页
        </li>
    </ol>

    @include('parts.carousel')

    <div class="row top20">
        @foreach($data as $cate_name => $articles)
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
        @endforeach

    </div>
</div>
@endsection

@extends('layouts.app')
@section('title')
  中医
@endsection
@section('keywords')
  中医
@endsection
@section('description')
  中医
@endsection

@section('content')
<div class="container">
    <ol class="breadcrumb">
    	<li><a href="/">懂点医</a></li>
        <li class="active">
            中医
        </li>
    </ol>
    @include('parts.carousel')
    <div class="panel panel-default top20">
        <div class="panel-heading">
            <h3 class="panel-title">
                中医文章
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
@endsection

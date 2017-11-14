@extends('layouts.app')
@section('title')
  {{ $tag->name }}
@endsection
@section('keywords')
  {{ $tag->name }}
@endsection
@section('description')
  {{ $tag->name }}
@endsection

@section('content')
<div class="container">
    <ol class="breadcrumb">
    	<li><a href="/">{{ config('app.name') }}</a></li>
        <li class="active">
            {{ $tag->name }}
        </li>
    </ol>
    @include('parts.carousel')
    <div class="panel panel-default top20">
        <div class="panel-heading">
            <h3 class="panel-title">
                标签 "{{ $tag->name }}" 的 文章
            </h3>
        </div>
        <div class="panel-body">
            <div class="list-group">
                @foreach($articles as $article)
                   @include('article.parts.article_item')
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

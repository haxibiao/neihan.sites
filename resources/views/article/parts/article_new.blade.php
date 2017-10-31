@extends('layouts.app')

@section('title')
   最新内容
@stop

@section('content')
    <div class="container">
  <ol class="breadcrumb">
    <li><a href="/">{{ config('app.name') }}</a></li>
    <li class="active">文章列表</li>
  </ol>

<div class="panel panel-default">
  <div class="panel-body">
    @foreach($articles as $article)
    <div class="media">
      @if(!empty($article->image_url))
      <a class="pull-left" href="/article/{{ $article->id }}">
        <img class="media-object" src="{{ get_small_image($article->image_url) }}" alt="{{ $article->title }}" style="max-width: 200px">
      </a>
      @endif
      <div class="media-body">
        <a href="/article/{{ $article->id }}">
          <h4 class="media-heading">{{ $article->title }}</h4>
        </a>
        <p>{{ $article->description }}</p>
        <p>{{ $article->created_at }}</p>
      </div>
    </div>
    @endforeach

    <p>
       {{ $articles->render() }}
    </p>
  </div>
</div>

</div>
@stop
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
                标签 "{{ $tag->name }}" 的文章
            </h3>
        </div>
        <div class="panel-body">
                @foreach($article_tags as $article_tag)
                    @include('article.parts.article_item', ['article' => $article_tag->article])
                @endforeach
        </div>
    </div>
    <div class="panel panel-default top20">
        <div class="panel-heading">
            <h3 class="panel-title">
                标签 "{{ $tag->name }}" 的图片
            </h3>
        </div>
        <div class="panel-body">
                @foreach($image_tags as $image_tag)
                    @include('image.parts.image_item', ['image' => $image_tag->image])
                @endforeach
        </div>
    </div>
</div>
@endsection

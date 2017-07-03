@extends('layouts.app')

@section('title')
    文章列表
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@section('content')

<div class="container">
  <ol class="breadcrumb">
    <li><a href="/">懂点医</a></li>
    <li class="active">文章列表</li>
  </ol>

@foreach($articles as $article)
<div class="media">
  @if(!empty($article->image_url))
  <a class="pull-left" href="/article/{{ $article->id }}">
    <img class="media-object" src="{{ $article->image_url }}" alt="{{ $article->title }}">
  </a>
  @endif
  <div class="media-body">
    <a href="/article/{{ $article->id }}">
      <h4 class="media-heading">{{ $article->title }}</h4>
    </a>
    <p>{{ $article->description }}</p>

      <a class="btn btn-primary pull-right left10" href="/article/{{ $article->id }}/edit" role="button" target="_blank">编辑</a>
      {!! Form::open(['method' => 'delete', 'route' => ['article.destroy', $article->id], 'class' => 'form-horizontal']) !!}
        {!! Form::submit('删除', ['class' => 'btn btn-danger pull-right']) !!}                
      {!! Form::close() !!}
    
  </div>
</div>
@endforeach

</div>

@endsection
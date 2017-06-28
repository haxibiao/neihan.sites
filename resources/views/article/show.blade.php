@extends('layouts.app')

@section('title')
  {{ $article->title }}
@endsection
@section('keywords')
  {{ $article->keywords }}
@endsection
@section('description')
  {{ $article->description }}
@endsection

@section('content')
<div class="container">
  <ol class="breadcrumb">
    <li><a href="/">懂点医</a></li>
    <li><a href="/{{ $article->category->name_en }}">{{ $article->category->name }}</a></li>
    <li class="active">{{ $article->title }}</li>
  </ol>

  <div class="jumbotron">
    <h1>{{ $article->title }}</h1>
    <p>
      分类: {{ $article->category->name }}
    </p>
    <p>
      标签: @foreach($article->tags as $tag) {{  $tag->name  }} @endforeach
    </p>
    <p>
      {!! $article->body !!}
    </p>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          作者
        </div>
        <div class="panel-body">
           @include('parts.user_item', ['user' => $article->user])
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">相关图片</h3>
        </div>
        <div class="panel-body">
          @foreach($article->images as $image) 
            <img src="{{ $image->path_small }}" alt="" class="img img-responsive col-xs-6 col-sm-4 col-md-3">
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
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
    <li><a href="/zhongyi">中医</a></li>
    <li class="active">{{ $article->title }}</li>
  </ol>

  <div class="jumbotron">
    <h1>{{ $article->title }}</h1>
    <p>
      {!! $article->body !!}
    </p>
    <p><a class="btn btn-primary btn-lg" href="#" role="button">全文</a></p>
  </div>
</div>
@endsection
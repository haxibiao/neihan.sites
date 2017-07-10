@extends('layouts.app')
@section('title')
  {{ $category->name }} 
@endsection
@section('keywords')
  {{ $category->name }} 
@endsection
@section('description')
  {{ $category->name }} 
@endsection

@section('content')
<div class="container">
    <ol class="breadcrumb">
    	<li><a href="/">{{ config('app.name') }}</a></li>
        <li class="active">
            {{ $category->name }}
        </li>
    </ol>
    
    <div class="row">
        <div class="col-md-8">
            @include('parts.carousel')
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        最新{{ $category->name }}文章
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
    </div>

    <div class="row">
        @foreach($data as $cate_name => $articles)
        @if(!$articles->isEmpty())
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
        @endif
        @endforeach
    </div>
</div>
@endsection

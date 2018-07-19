@extends('layouts.app')

@section('title')
    管理文章
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@section('content')

<div class="container">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 style="margin:0;display:inline-block">管理文章</h3>
        <basic-search api="/article?q="></basic-search>
    </div>
    
  <div class="panel-body">
    @foreach($articles as $article)
    <div class="media">
      @if($article->hasImage())
      <a class="pull-left" href="/article/{{ $article->id }}">
        <img class="media-object" src="{{ $article->primaryImage() }}" alt="{{ $article->title }}" style="max-width: 200px">
      </a>
      @endif
      <div class="media-body">
        <a href="/article/{{ $article->id }}">
          <h4 class="media-heading">{{ $article->title }}</h4>
        </a>
        <p>{{ $article->description() }}</p>

        <div class="pull-right">
          <a class="btn btn-sm btn-primary" href="/article/{{ $article->id }}/edit" role="button" target="_blank">编辑</a> 
          <br/>
          <br/>
          {!! Form::open(['method' => 'delete', 'route' => ['article.destroy', $article->id], 'class' => 'form-horizontal']) !!}
          {!! Form::submit('删除', ['class' => 'btn btn-sm btn-danger']) !!}                
          {!! Form::close() !!}
        </div>
        
      </div>
    </div>
    @endforeach

    <p>
      {{ $articles->render() }}
    </p>
  </div>
</div>

</div>

@endsection
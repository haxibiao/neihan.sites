@extends('layouts.app')

@section('title')
    管理草稿
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@section('content')

<div class="container">
  <div class="panel panel-default">
    <div class="panel-heading"><h3>管理草稿</h3></div>
  <div class="panel-body">
    @foreach($articles as $article)
    <div class="media">
      @if(!empty($article->hasImage()))
      <a class="pull-left" href="/article/{{ $article->id }}">
        <img class="media-object" src="{{ $article->primaryImage() }}" alt="{{ $article->title }}" style="max-width: 200px">
      </a>
      @endif
      <div class="media-body">
        <a href="/article/{{ $article->id }}">
          <h4 class="media-heading">{{ $article->title }}</h4>
        </a>
        <p>{{ $article->get_description() }}</p>
        @if($article->delay_time)
          <p class="small text-info">已启动定时发布于: {{ $article->delay_time }} ({{ diffForHumansCN($article->delay_time) }})</p>
        @endif

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
@extends('layouts.app')

@section('title')
    {{ $user->name }}的文章
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li><a href="/user/{{ $user->id }}">{{ $user->name }}</a></li>
      </ol>
    <div class="panel panel-default">
        <div class="panel-body">
            <img alt="" class="img img-circle" src="{{ get_avatar($user) }}">
                <h4>
                    {{ $user->name }}
                </h4>
                <p>
                    {{ $user->email }}
                </p>
                <p>
                	{{ $user->introduction }}
                </p>
            </img>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
        <a class="pull-right" href="/user/{{ $user->id }}/articles">更多</a>
            <h3 class="panel-title" style="line-height: 30px">文章({{ $data['articles']->total() }})</h3>
        </div>
        <div class="panel-body">
            @foreach($data['articles'] as $article)
                @include('article.parts.article_item')
            @endforeach   
            <p>
                {!! $data['articles']->render() !!}
            </p>         
        </div>
    </div>
</div>
@endsection

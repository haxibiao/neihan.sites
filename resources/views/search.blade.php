@extends('layouts.app')

@section('title')
    搜索 
@endsection

@section('content')
<div class="container">
    <div class="row">
    <div class="col-xs-12 col-sm-6">
        <form action="/search" class="center-block" method="get">
        <div class="form-group pull-left right10" style="width: 70%">
            <input class="form-control" name="q" placeholder="搜索..." type="text" required="required">
            </input>
        </div>
        <button class="btn btn-default" type="submit">
            搜索
        </button>
    </form>
    </div>
    </div>
    @if(!$data['queries']->isEmpty())
    <div class="panel panel-default">
        <div class="panel-body">
            热门搜索: 
            @foreach($data['queries'] as $query)
            <a href="/search?q={{ $query->query }}">
                {{ $query->query }}
                <span class="badge">
                    {{ $query->results }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                "{{ $data['query'] }}" 搜索结果(共{{ $data['total'] }}条)
            </h3>
        </div>
        <div class="panel-body">
            @foreach($data['articles'] as $article)
                    @include('article.parts.article_item')
                @endforeach
            <p>
                {!! $data['articles']->appends(['q'=>$data['query']])->render() !!}
            </p>
        </div>
    </div>
</div>
@stop

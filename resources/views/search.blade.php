@extends('layouts.app')

@section('title')
    搜索 
@endsection

@section('content')
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">"{{ $data['query'] }}" 搜索结果</h3>
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
@stop
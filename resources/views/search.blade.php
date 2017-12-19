@extends('layouts.app')

@section('title')
    搜索 
@endsection

@section('content')
<div id="bookmarks" class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                "{{ $data['query'] }}" 搜索结果(共{{ $data['total'] }}条)
            </h3>
        </div>
        <div class="panel-body">
            <div class="main">
                <div>
                    <ul class="article_list">
                    @foreach($data['articles'] as $article)
                         @include('parts.list.article_category', ['articles'=>$data['articles'] ,'search'=>true])
                    @endforeach
                    </ul>
                </div>
            </div>
            <p>
                {!! $data['articles']->appends(['q'=>$data['query']])->render() !!}
            </p>
        </div>
    </div>
</div>
@stop

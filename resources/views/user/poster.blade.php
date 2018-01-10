@extends('layouts.app')

@section('title')
    搜索
@endsection

@section('content')
<div id="search" class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                   允许上首页的文章最多只允许8条
            </h3>
        </div>
        <div class="panel-body">
            <div class="main">
                <div>
                    <ul class="article_list">
                         @include('parts.list.article_category', ['articles'=>$data['articles']])
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

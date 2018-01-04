@extends('layouts.app')

@section('title')
    新上榜 - 爱你城
@stop
@section('content')
<div id="hot_articles">
    <div class="container">
        <div class="row">
            {{-- 左侧 --}}
            <div class="main col-xs-12 col-sm-8">
            	<img src="/images/board_01.png" class="tag_banner" />
                {{-- 文章摘要 --}}
                @include('category.parts.category_item',['articles'=>$articles])
            </div>
            {{-- 右侧 --}}
            <div class="aside col-sm-4">
                {{-- 推荐作者 --}}
                @if(Auth::check())
                <recommend-authors></recommend-authors>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

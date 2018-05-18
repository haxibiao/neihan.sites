@extends('v2.layouts.app')

@section('title')
    7日热门 - 爱你城
@stop
@section('content')
<div id="hot_articles">
    <div class="container">
        <div class="row">
            {{-- 左侧 --}}
            <div class="main col-xs-12 col-sm-8">
            	<img src="/images/board_02.png" class="tag_banner" />
                {{-- 文章摘要 --}}
                @include('v2.parts.article.article_list_nocategory')
                <a class="load_more" href="javascript:;">
                    阅读更多
                </a>
            </div>
            {{-- 右侧 --}}
            <div class="aside col-sm-4">
                {{-- 推荐作者 --}}
                @include('v2.parts.recommended_authors')
            </div>
        </div>
    </div>
</div>
@stop

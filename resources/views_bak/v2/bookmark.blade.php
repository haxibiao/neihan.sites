@extends('v2.layouts.app')

@section('title')
    收藏的文章 - 爱你城
@stop
@section('content')
<div id="bookmark">
    <div class="container">
        <img class="tag_banner" src="/images/bookmark.png"/>
        @include('v2.parts.article.article_list_bookmark')
    </div>
</div>
@stop

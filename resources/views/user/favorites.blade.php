@extends('layouts.app')

@section('title')
    收藏的文章 - 爱你城
@stop
@section('content')
<div id="bookmark">
    <div class="container">
        <img class="tag_banner" src="/images/bookmark.png"/>
      @foreach($data['fav_articles'] as $favorite)
         @php
         	$article=$favorite->faved;
         @endphp
        @include('parts.list.article_bookmark')
      @endforeach
    </div>
</div>
@stop

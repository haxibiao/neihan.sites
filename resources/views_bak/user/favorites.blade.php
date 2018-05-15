@extends('layouts.app')

@section('title')
    收藏的文章 - 爱你城
@stop
@section('content')
<div id="bookmark">
    <div class="container">
        <div class="page_banner">
            <div class="banner_img collect_note">
                <div>
                    <i class="iconfont icon-biaoqian"></i>
                    <span>收藏的文章</span>
                </div>
            </div>
        </div>
      @foreach($data['fav_articles'] as $favorite)
         @php
         	$article=$favorite->faved;
         @endphp
        @include('parts.list.article_bookmark')
      @endforeach
    </div>
</div>
@stop

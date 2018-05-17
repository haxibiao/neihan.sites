@extends('layouts.app')

@section('title')
   {{ config('app.name') }}-{{ config('seo.title') }} 
@stop

@section('keywords'){{ config('seo.keywords') }}
@stop

@section('description'){{ config('seo.description') }}
@stop

@section('content')

<div id="index">
      {{-- 轮播图 --}}
      @if(config('editor.ui.show_poster'))
        @include('index.parts.poster')
      @endif

     <section class="clearfix">
        {{-- 主要内容 --}}
        <div class="main col-sm-7">
          {{-- 推荐专题 --}}
          @include('index.parts.recommend_categories')
          <recommend-category></recommend-category>
          <div class="division-line"></div>

          {{-- top 4 videos --}}
           <div class="row">
             @foreach($data->videos as $video)
             <div class="col-md-3 col-xs-6">
              <div class="media">
                  <a class="pull-left" href="/video/{{$video->id}}">
                      <img class="media-object" src="{{ $video->cover() }}" alt="{{ $video->title }}">
                  </a>
                  <div class="media-body">
                      <h4 class="media-heading">{{ $video->title }}</h4>
                      <p>{{ $video->description }}</p>
                  </div>
              </div>
            </div>
              @endforeach
           </div>

          {{-- 文章列表 --}}
         <ul class="article-list">              
            {{-- 文章 --}}          
            @each('parts.article_item', $data->articles, 'article')

            {{-- 登录后才加载更多 --}}
            @if(Auth::check())
              <article-list api="/" start-page="2" />
            @else 
              <div>
                {!! $data->articles->links() !!}
              </div>
            @endif
            
          </ul>
        </div>
        {{-- 侧栏 --}}
        <div class="aside col-sm-4 col-sm-offset-1 hidden-xs">
            @include('index.parts.trendings')
            {{-- app --}}
            @include('index.parts.download_app')
            {{-- 日报 --}}
            {{-- @include('index.parts.daily') --}}
            {{-- 推荐作者 --}}
            @if(Auth::check())
            <recommend-authors></recommend-authors>
            @endif
        </div>
   </section>
  {{-- 网站底部信息 --}}
  @include('parts.footer')
</div>

@endsection

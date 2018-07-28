@extends('layouts.app')

@section('title')
   {{ config('app.name') }}-{{ config('seo.'.get_domain_key().'.title') }} 
@stop

@section('keywords'){{ config('seo.'.get_domain_key().'.keywords') }}
@stop

@section('description'){{ config('seo.'.get_domain_key().'.description') }}
@stop

@section('content')

<div id="index">
      {{-- 轮播图 --}}
      <div class="wrap clearfix">
        {{-- 主要内容 --}}
        <div class="main sm-left">
          <div class="poster-container">
            @if(config('editor.ui.show_poster'))
              @include('index.parts.poster')
            @endif
          </div>
          {{-- 推荐专题 --}}
          @include('index.parts.recommend_categories')
          <recommend-category></recommend-category>
          {{-- top 4 videos --}}
          <div class="row videos distance">
           <div class="vd-head">
              <h3 class="vd-title">
                <span class="title-icon">
                  <i class="iconfont icon-huo"></i>推荐视频
                </span>
              </h3>
               <a href="/video" class="more-video">
                <p>
                    更多
                    <i class="iconfont icon-youbian"></i>
                </p>
              </a>
            </div> 
            @include('index.parts.top_videos', ['videos' => $data->videos])
          </div>
          {{-- 文章列表 --}}
         <ul class="article-list">              
            {{-- 文章 --}}          
            @each('parts.article_item', $data->sticks, 'article') 
            @each('parts.article_item', $data->articles, 'article') 

            {{-- 登录后才加载更多 --}}
            @if(Auth::check())
              <article-list api="/" start-page="2" is-desktop="{{ \Agent::isDeskTop() == 1 }}" 
              video-duration="{{$video->duration}}"/> 
            @else 
              <div>
                {!! $data->articles->links() !!}
              </div>
            @endif
            
          </ul>
        </div>
        {{-- 侧栏 --}}
        <div class="aside sm-right hidden-xs">
            @include('index.parts.trendings') 
            {{-- app --}}
            @include('index.parts.download_app') 
            {{-- 日报 --}}
            {{-- @include('index.parts.daily') --}}
            {{-- 推荐作者 --}}
            {{-- @if(Auth::check()) --}}
            <recommend-authors></recommend-authors>
            {{-- @endif --}}
        </div>
      </div>
      {{-- 网站底部信息 --}}
      @include('parts.footer')
</div>

@endsection

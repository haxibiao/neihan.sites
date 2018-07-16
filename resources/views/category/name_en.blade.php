@extends('layouts.app')

@section('title') {{ $category->name }} - {{ env('APP_NAME') }} @endsection
@section('keywords') {{ $category->name }} @endsection
@section('description') {{ $category->description?$category->description:config('seo.'.get_domain_key().'.description') }} @endsection
 
@section('content')
<div id="category">
    <div class="clearfix">
        <div class="main col-sm-7">
            {{-- 分类信息 --}}
            @include('category.parts.information')
            {{-- 视频文章内容--}}
            <ul id="select-menu"  class="nav nav-pills"  role="tablist">
              <li role="presentation" class="active">
                 <a href="#article" aria-controls="article" role="tab" data-toggle="tab" class="article">文章</a>
              </li>
              <li role="presentation" >
                <a href="#video-list" aria-controls="video-list" role="tab" data-toggle="tab" class="video">视频</a>
              </li>
            </ul>
            {{-- 内容 --}}
            <div class="article-list tab-content">
              <ul role="tabpanel" class="fade in tab-pane active" id="article">
                <div class="content"> 
                     <!-- Nav tabs -->
                     <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                       <li role="presentation" class="active">
                        <a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>新评论</a>
                       </li>
                       <li role="presentation">
                        <a href="#include" aria-controls="include" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>新收录</a>
                       </li>
                       <li role="presentation">
                        <a href="#hot" aria-controls="hot" role="tab" data-toggle="tab"><i class="iconfont icon-huo"></i>热门</a>
                       </li>
                     </ul>
                     <!-- Tab panes -->
                     <div class="article-list tab-content">
                       <ul role="tabpanel" class="fade in tab-pane active" id="comment">
                           @if( count($data['commented'])==0)
                              <blank-content></blank-content>
                           @else 
                            @each('parts.article_item', $data['commented'], 'article')
                             @if(!Auth::check())
                             <div>{!! $data['commented']->links() !!}</div>
                             @else
                             <article-list api="/{{ $category->name_en }}?commented=1" start-page="2" not-empty="{{count($data['commented'])>0}}"/>
                            @endif
                           @endif
                       </ul>
                       <ul role="tabpanel" class="fade tab-pane" id="include">
                           @if( count($data['collected'])==0)
                              <blank-content></blank-content>
                           @else 
                                @each('parts.article_item', $data['collected'], 'article')
                               @if(!Auth::check())
                                <div>{!! $data['collected']->fragment('include')->links() !!}</div>
                               @else
                                <article-list api="/{{ $category->name_en }}?collected=1" start-page="2" not-empty="{{count($data['collected'])>0}}"/>
                               @endif
                           @endif
                       </ul>
                       <ul role="tabpanel" class="fade tab-pane" id="hot">
                           @if( count($data['hot'])==0)
                              <blank-content></blank-content>
                           @else 
                             @each('parts.article_item', $data['hot'], 'article')
                             @if(!Auth::check())
                                <div>{!! $data['hot']->fragment('hot')->links() !!}</div>
                             @else
                                <article-list api="/{{ $category->name_en }}?hot=1" start-page="2" not-empty="{{count($data['hot'])>0}}"/>
                             @endif
                           @endif
                       </ul>
                     </div>
                </div>
              </ul>
              <ul role="tabpanel" class="fade tab-pane" id="video-list">
                <div class="content"> 
                 <!-- Nav tabs -->
                  <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                      <a href="#video_new" aria-controls="comment" role="tab" data-toggle="tab">
                        <i class="iconfont icon-shipin1"></i>最新
                      </a>
                    </li>
                    <li role="presentation">
                      <a href="#video_hot" aria-controls="include" role="tab" data-toggle="tab">
                        <i class="iconfont icon-huo"></i>热门
                      </a>
                    </li>
                  </ul>
                 <!-- Tab panes -->
                  <div class="article-list tab-content">
                     <ul role="tabpanel" class="fade in tab-pane active" id="video_new">
                        @if( count($data['video_new'])==0)
                          <blank-content></blank-content>
                        @else 
                          @foreach($data['video_new'] as $article)
                           <li class="col-xs-6 col-md-4 video">
                              <div class="video-item vt">
                                <div class="thumb">
                                  <a href="{{ $article->content_url() }}" target="_blank">
                                    <img src="{{ $article->cover() }}" alt="{{ $article->title }}">
                                    <i class="duration">
                                      {{-- 持续时间 --}}  
                                      @sectominute($article->video->duration)
                                    </i>
                                  </a>
                                </div>
                                <ul class="info-list">
                                  <li class="video-title">
                                    <a href="{{ $article->content_url() }}">{{ $article->title }}</a>
                                  </li>
                                  <li>
                                    {{-- 播放量 --}}
                                    <p class="subtitle single-line">{{ $article->hits }}次播放</p>
                                  </li>
                                </ul>
                              </div>
                            </li>
                           @endforeach
                           <div>{!! $data['video_new']->fragment('video_new')->links() !!}</div>
                        @endif
                     </ul>
                     <ul role="tabpanel" class="fade tab-pane" id="video_hot">  
                        @if( count($data['video_hot'])==0)
                          <blank-content></blank-content>
                        @else 
                          @foreach($data['video_hot'] as $article)
                           <li class="col-xs-6 col-md-4 video">
                              <div class="video-item vt">
                                <div class="thumb">
                                  <a href="{{ $article->content_url() }}" target="_blank">
                                    <img src="{{ $article->cover() }}" alt="{{ $article->title }}">
                                    <i class="duration">
                                      {{-- 持续时间 --}}  
                                      @sectominute($article->video->duration)
                                    </i>
                                  </a>
                                </div>
                                <ul class="info-list">
                                  <li class="video-title">
                                    <a href="{{ $article->content_url() }}">{{ $article->title }}</a>
                                  </li>
                                  <li>
                                    {{-- 播放量 --}}
                                    <p class="subtitle single-line">{{ $article->hits }}次播放</p>
                                  </li>
                                </ul>
                              </div>
                            </li>
                           @endforeach
                           <div>{!! $data['video_hot']->fragment('video_hot')->links() !!}</div>
                        @endif
                     </ul>
                  </div>
              </ul>
            </div>
        </div>
        <div class="aside col-sm-4 col-sm-offset-1">
            @include('category.parts.description')
            @include('parts.share')
            @include('category.parts.admins')
            @include('category.parts.authors')
            @include('category.parts.followers')
            @include('category.parts.related')
        </div>
    </div>
</div>
@endsection   

@push('modals')
  {{-- 分享到微信 --}}
  <modal-share-wx url="{{ url()->full() }}" aid="{{ $category->id }}"></modal-share-wx>
@endpush

@push('scripts')
<script type="text/javascript">
  $(function(){  
    var url = window.location.href;
    if( url.includes("video") ){
      $("[href='#video']").click();  
    }
    if( url.includes("hot") ){
      $("[href='#hot']").click(); 
    }
    if( url.includes("include") ){
      $("[href='#include']").click(); 
    }
    if( url.includes("video_hot") ){
      $("[href='#video-list']").click(); 
      $("[href='#video_hot']").click(); 
    }
    if( url.includes("video_new") ){
      $("[href='#video-list']").click(); 
      $("[href='#video_new']").click(); 
    }
  });
</script>
@endpush


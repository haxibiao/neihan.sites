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
            {{-- 内容 --}}
            <div class="article-list tab-content">
              {{-- 视频文章内容--}}
                <div class="content"> 
                     <!-- Nav tabs -->
                     <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                       <li role="presentation" class="active">
                        <a href="#include" aria-controls="include" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>作品</a>
                       </li>
                       <li role="presentation">
                        <a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>新评论</a>
                       </li>                       
                       <li role="presentation">
                        <a href="#hot" aria-controls="hot" role="tab" data-toggle="tab"><i class="iconfont icon-huo"></i>热门</a>
                       </li>
                     </ul>
                     <!-- Tab panes -->
                     <div class="article-list tab-content">
                      <ul role="tabpanel" class="fade in tab-pane active" id="include">
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
                       <ul role="tabpanel" class="fade tab-pane " id="comment">
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


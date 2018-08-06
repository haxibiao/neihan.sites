@php
    $article = $video->article;
@endphp

@extends('layouts.black')

@section('title')
	视频动态: {{ $video->article->title }} - @if($video->article && $category){{ $category->name }}@endif {{ env('APP_NAME') }}
@stop

@push('seo_og_result') 
@if($video->article)
<meta property="og:type" content="video" />
<meta property="og:url" content="https://{{ get_domain() }}/video/{{ $video->article->video_id }}" />
<meta property="og:title" content="{{ $video->article->title }}" />
<meta property="og:description" content="{{ $video->article->description() }}" />
<meta property="og:image" content="{{ $video->article->cover() }}" />
<meta name="weibo: article:create_at" content="{{ $video->article->created_at }}" />
<meta name="weibo: article:update_at" content="{{ $video->article->updated_at }}" />
@endif
@endpush  

@section('logo')
    <a class="logo" href="/">
        <img src="/logo/{{ get_domain() }}.text.png" alt="">
    </a>
@stop

@section('content')
<div class="player-container">
    
    <div class="playerBox">
        <div class="container">
            <div class="player-basic clearfix">
                <div class="playerArea col-sm-8">
                    <div class="h5-player">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video controls="" poster="{{ $video->article->cover() }}" preload="auto" autoplay="true">
                                <source src="{{ $video->url() }}" type="video/mp4"> 
                                </source> 
                            </video>
                        </div> 
                    </div>
                    <div class="h5-option"> 
                       <like id="{{ $video->article->id }}" type="article" is-login="{{ Auth::check() }}"></like> 
                       @if(canEdit($article))
                            <a class="btn-base btn-light btn-sm editor-btn" href="/video/{{ $video->id }}/edit">编辑视频动态</a>
                        @endif 
                       @include('video.parts.share')
                    </div>
                </div>
                
            </div>
            <div class="video-info">
                <div class="video-title">
                    {{ $video->article->title }}
                </div>                 
                <div class="video-description">
                    {{ $video->article->body }}
                </div>
                <div class="video-right">
                   @include('video.parts.author')
                    <div class="listArea">
                        {{-- 作者的其他视频 --}}
                        <authors-video user-id={{ $video->user_id }}></authors-video>
                    </div> 
                </div>
                
                <div class="video-categories" style="margin-top:20px">
                    <h4>相关的专题</h4>
                @foreach($categories as $category)
                    <div class="pull-left">
                        @include('video.parts.category_item')
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="sectionBox">
        <div class="container clearfix">
            <div class="row">
                <div class="col-sm-8">  
                    {{-- 评论中心 --}} 
                    <comments comment-replies={{ $video->article->count_replies }} type="articles" id="{{ $video->article->id }}" author-id="{{ $video->user_id }}"></comments>
                </div>
                <div class="guess-like col-sm-4 hidden-xs">
                    {{-- 其他推荐的视频 --}}
                    <authors-video category-id={{ $video->id }}></authors-video>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        @include('parts.footer')
    </div>
    

</div>
@stop

@push('scripts')
    @include('parts.js_for_app')
@endpush
@push('modals')
  {{-- 分享到微信 --}}
  <modal-share-wx  url="{{ url()->full() }}" aid="{{ $video->article->video_id }}"></modal-share-wx>
@endpush
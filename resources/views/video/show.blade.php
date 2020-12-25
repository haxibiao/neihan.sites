@extends('layouts.video')

@section('title')
    {{ $video->title ?? '视频'.$video->id}}
@stop

@push('seo_og_result')
    <meta property="og:type" content="video" />
    <meta property="og:url" content="{{ $video->url }}" />
    <meta property="og:title" content="{{ $video->title }}" />
    <meta property="og:description" content="{{ $video->title }}" />
    <meta property="og:image" content="{{ $video->cover }}" />
    <meta name="weibo: article:create_at" content="{{$video->created_at }}" />
    <meta name="weibo: article:update_at" content="{{$video->updated_at }}" />
@endpush

@section('content')
    <div class="player-container">

        <div class="playerBox">
            <div class="author-info">
                @include('video.parts.author')
            </div>
            <div class="player-basic clearfix">
                <div class="playerArea col-sm-8">
                    <div class="h5-player">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video controls="" poster="{{ $video->cover }}" preload="auto" autoplay="true">
                                <source src="{{ $video->url }}" type="video/mp4">
                                </source>
                            </video>
                        </div>
                    </div>
                    <div class="video-body">
                        {{-- 所有合集信息 --}}
                        <span class="content">
                            {{ $video->title }}
                        </span>
                    </div>
                    <div class="h5-option">
                        {{-- 喜欢 --}}
                        {{-- 评论部分 --}}
                        {{-- 分享部分 --}}
                    </div>
                    <div class="pc-option">
                        {{-- 赞赏 --}}
                        {{-- 喜欢 --}}
                        @include('video.parts.share')
                    </div>
                </div>
                <div class="video-right">
                    <div class="listArea">
                        {{-- 作者的其他视频 --}}
                        <authors-video user-id="{{ $video->user_id }}" video-id="{{ $video->id }}"
                            related-page="{{ request('related_page') }}"></authors-video>
                    </div>
                </div>
            </div>
            <div class="video-title">
                {{ $video->title }}
                <div class="video-info">
                    {{-- 当前主合集 --}}
                </div>
            </div>
            <div class="video-relevant">
                <div class="author-info">
                    @include('video.parts.author')
                    {{-- 赞赏支持 --}}
                </div>
                <authors-video user-id="{{ $video->user_id }}" video-id="{{ $video->id }}"></authors-video>
                {{-- 相关的专题 --}}
            </div>
            <div class="video-info">
                {{-- 专题 合集？ --}}
                <i class="iconfont icon-shijian"></i>
                <span>发布于：{{ $video->createdAt() }}</span>
                <i class="iconfont icon-shipin1"></i>
                <span class="hits">{{ $video->hits ?? rand(1000,50000) }}次播放</span>
            </div>
        </div>
        <div class="sectionBox">
            <div class="container clearfix">
                <div class="row">
                    <div class="col-sm-8">
                        {{-- 评论中心 --}}                        
                    </div>
                    <div class="guess-like  hidden-xs">
                        {{-- 其他推荐的视频 --}}
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="share-module">
        <div class="module-share-h3">分享到....</div>
        <div>@include('video.parts.share', ['subject' => $video->title, 'url'=>url('/video/'.$video->id)])</div>
        <close-share></close-share>
    </div>
    <div id="pageLayout">

    </div>
@stop

@push('modals')
    {{-- 分享到微信 --}}
    <modal-share-wx url="{{ url()->full() }}" aid="{{ $video->id }}"></modal-share-wx>
@endpush

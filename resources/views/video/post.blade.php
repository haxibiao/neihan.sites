@php
$post = $video->post;
@endphp

@extends('layouts.video')

@section('title')
    {{ $post->content}}
@stop

@push('seo_og_result')
    @if ($video->post)
        <meta property="og:type" content="video" />
        <meta property="og:url" content="https://{{ get_domain() }}/video/{{ $video->id }}" />
        <meta property="og:title" content="{{$post->content }}" />
        <meta property="og:description" content="{{$post->description }}" />
        <meta property="og:image" content="{{ $video->cover }}" />
        <meta name="weibo: article:create_at" content="{{$post->created_at }}" />
        <meta name="weibo: article:update_at" content="{{$post->updated_at }}" />
    @endif
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
                        @foreach ($post->collections as $collection)
                            <a href="/category/{{ $collection->id }}" class="category-name"
                                title="{{ $collection->id }}:{{ $collection->name }}">
                                <span class="name">#{{ $collection->name }}</span>
                            </a>
                        @endforeach
                        <span class="content">
                            {{$post->description }}
                        </span>
                    </div>
                    <div class="h5-option">
                        <video-like id="{{ $post->id }}" type="posts" is-login="{{ Auth::check() }}">
                        </video-like>


                        <div class="comments">
                            {{-- <i class="iconfont icon-xinxi2"></i>
                            <p>{{ $post->count_replies??0 }}</p> --}}
                            <to-comment comment-replies={{ $post->count_replies??0 }}></to-comment>
                        </div>
                        {{-- <div class="share">
                            <share-module></share-module>
                        </div> --}}
                        {{-- @include('video.parts.share') --}}
                    </div>
                    <div class="pc-option">
                        @if (!$post->isSelf())
                            @if ($post->user && $post->user->enable_tips)
                                <a class="btn btn-warning" data-target=".modal-admire" data-toggle="modal">赞赏支持</a>
                                <modal-admire article-id="{{ $post->id }}"></modal-admire>
                            @endif
                        @endif
                        <like id="{{ $post->id }}" type="posts" is-login="{{ Auth::check() }}"></like>

                        @include('video.parts.share')
                    </div>
                </div>
                <div class="video-right">
                    <div class="listArea">
                        {{-- 作者的其他视频 --}}
                        <authors-video user-id="{{ $video->user_id }}" video-id="{{ $video->id }}"
                            related-page="{{ $data['related_page'] }}"></authors-video>
                    </div>
                </div>
            </div>
            <div class="video-title">
                {{ $post->body }}
                <div class="video-info">
                    @if (!empty($collection))
                        <a href="/category/{{ $collection->id }}" class="category-name">合集: {{ $collection->name }}</a>
                    @endif
                </div>
            </div>
            <div class="video-relevant">
                <div class="author-info">
                    @include('video.parts.author')
                    {{-- <div class="admire">
                        @if (!$post->isSelf())
                            @if ($post->user && $post->user->enable_tips)
                                <a class="btn-base btn-theme" data-target=".modal-admire" data-toggle="modal">赞赏支持</a>
                                <modal-admire article-id="{{ $post->id }}"></modal-admire>
                            @endif
                        @endif
                    </div> --}}
                </div>
                <authors-video user-id="{{ $video->user_id }}" video-id="{{ $video->id }}"></authors-video>
                {{-- <div class="video-categories" style="margin-top:20px">
                    <h4>相关的专题</h4>
                    @foreach ($collections as $collection)
                        <div class="pull-left">
                            @include('video.parts.category_item')
                        </div>
                    @endforeach
                </div> --}}
            </div>
            {{-- <div class="video-info">
                @if (!empty($collection))
                    <a href="/category/{{ $collection->id }}" class="category-name">{{ $collection->name }}</a>
                @endif
                <i class="iconfont icon-shijian"></i>
                <span>发布于：{{ $video->createdAt() }}</span>
                <i class="iconfont icon-shipin1"></i>
                <span class="hits">{{ $post->hits??0 }}次播放</span>
            </div> --}}
        </div>
        <div class="sectionBox">
            <div class="container clearfix">
                <div class="row">
                    <div class="col-sm-8">
                        {{-- 评论中心 --}}
                        <comments comment-replies={{ $post->count_replies??0 }} type="posts"
                            id="{{ $post->id }}" author-id="{{ $video->user_id }}"></comments>
                    </div>
                    <div class="guess-like  hidden-xs">
                        {{-- 其他推荐的视频 --}}
                        <authors-video category-id="{{ $post->collection_id }}" video-id="{{ $video->id }}"
                            related-page="{{ $data['related_page'] }}"></authors-video>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="share-module">
        <div class="module-share-h3">分享到....</div>
        <div>@include('video.parts.share', ['subject' => $post->content, 'url'=>url('/video/'.$video->id)])</div>
        <close-share></close-share>
    </div>
    <div id="pageLayout">

    </div>
@stop

@push('scripts')

    @if (Auth::check())
        <script type="text/javascript">
            var at_config = {
                at: "@",
                {
                    {
                        --这个是触发弹出菜单的按键--
                    }
                }
                data: window.tokenize('/api/related-users'),
                insertTpl: '<span data-id="${id}">@${name}</span>',
                displayTpl: "<li > ${name} </li>",
                limit: 200
            }
            $('#editComment').atwho(at_config); // 初始化

        </script>
    @endif
@endpush
@push('modals')
    {{-- 分享到微信 --}}
    <modal-share-wx url="{{ url()->full() }}" aid="{{ $post->video_id }}"></modal-share-wx>
@endpush

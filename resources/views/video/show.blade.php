@php
$article = $video->article;
@endphp

@extends('layouts.video')

@section('title')
    {{ $article->subject ?: $article->summary }}
    -{{ empty($article->category) ? seo_site_name() : $article->category->name }}
@stop

@push('seo_og_result')
    @if ($video->article)
        <meta property="og:type" content="video" />
        <meta property="og:url" content="https://{{ get_domain() }}/video/{{ $video->article->video_id }}" />
        <meta property="og:title" content="{{ $video->article->subject }}" />
        <meta property="og:description" content="{{ $video->article->summary }}" />
        <meta property="og:image" content="{{ $video->coverUrl }}" />
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
            <div class="author-info">
                @include('video.parts.author')
            </div>
            <div class="player-basic clearfix">
                <div class="playerArea col-sm-8">
                    <div class="h5-player">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video controls="" poster="{{ $video->coverUrl }}" preload="auto" autoplay="true">
                                <source src="{{ $video->url }}" type="video/mp4">
                                </source>
                            </video>
                        </div>
                    </div>
                    <div class="video-body">
                        @foreach ($article->categories->unique() as $category)
                            <a href="/category/{{ $category->id }}" class="category-name"
                                title="{{ $category->id }}:{{ $category->name }}">
                                <span class="name">#{{ $category->name }}</span>
                            </a>
                        @endforeach
                        <span class="content">
                            {{ $video->article->description }}
                        </span>
                    </div>
                    <div class="h5-option">
                        <video-like id="{{ $video->article->id }}" type="article" is-login="{{ Auth::check() }}">
                        </video-like>
                        {{-- @if (canEdit($article))
                            <a class="btn-base btn-light btn-sm editor-btn" href="/video/{{ $video->id }}/edit">编辑视频动态</a>
                        @endif --}}

                        <div class="comments">
                            {{-- <i class="iconfont icon-xinxi2"></i>
                            <p>{{ $video->article->count_replies }}</p> --}}
                            <to-comment comment-replies={{ $video->article->count_replies }}></to-comment>
                        </div>
                        {{-- <div class="share">
                            <share-module></share-module>
                        </div> --}}
                        {{-- @include('video.parts.share') --}}
                    </div>
                    <div class="pc-option">
                        @if (!$video->article->isSelf())
                            @if ($video->article->user && $video->article->user->enable_tips)
                                <a class="btn btn-warning" data-target=".modal-admire" data-toggle="modal">赞赏支持</a>
                                <modal-admire article-id="{{ $video->article->id }}"></modal-admire>
                            @endif
                        @endif
                        <like id="{{ $video->article->id }}" type="article" is-login="{{ Auth::check() }}"></like>
                        @if (canEdit($article))
                            <a class="btn-base btn-light btn-sm editor-btn" href="/video/{{ $video->id }}/edit">编辑视频动态</a>
                        @endif
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
                {{ $video->article->body }}
                <div class="video-info">
                    @if (!empty($category))
                        <a href="/category/{{ $category->id }}" class="category-name">分类: {{ $article->category->name }}</a>
                    @endif
                </div>
            </div>
            <div class="video-relevant">
                <div class="author-info">
                    @include('video.parts.author')
                    {{-- <div class="admire">
                        @if (!$video->article->isSelf())
                            @if ($video->article->user && $video->article->user->enable_tips)
                                <a class="btn-base btn-theme" data-target=".modal-admire" data-toggle="modal">赞赏支持</a>
                                <modal-admire article-id="{{ $video->article->id }}"></modal-admire>
                            @endif
                        @endif
                    </div> --}}
                </div>
                <authors-video user-id="{{ $video->user_id }}" video-id="{{ $video->id }}"></authors-video>
                {{-- <div class="video-categories" style="margin-top:20px">
                    <h4>相关的专题</h4>
                    @foreach ($categories as $category)
                        <div class="pull-left">
                            @include('video.parts.category_item')
                        </div>
                    @endforeach
                </div> --}}
            </div>
            {{-- <div class="video-info">
                @if (!empty($category))
                    <a href="/category/{{ $category->id }}" class="category-name">{{ $article->category->name }}</a>
                @endif
                <i class="iconfont icon-shijian"></i>
                <span>发布于：{{ $video->createdAt() }}</span>
                <i class="iconfont icon-shipin1"></i>
                <span class="hits">{{ $article->hits }}次播放</span>
            </div> --}}
        </div>
        <div class="sectionBox">
            <div class="container clearfix">
                <div class="row">
                    <div class="col-sm-8">
                        {{-- 评论中心 --}}
                        <comments comment-replies={{ $video->article->count_replies }} type="articles"
                            id="{{ $video->article->id }}" author-id="{{ $video->user_id }}"></comments>
                    </div>
                    <div class="guess-like  hidden-xs">
                        {{-- 其他推荐的视频 --}}
                        <authors-video category-id="{{ $video->article->category_id }}" video-id="{{ $video->id }}"
                            related-page="{{ $data['related_page'] }}"></authors-video>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="share-module">
        <div class="module-share-h3">分享到....</div>
        <div>@include('video.parts.share', ['subject' => $video->name, 'url'=>url('/video/'.$video->id)])</div>
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
    <modal-share-wx url="{{ url()->full() }}" aid="{{ $video->article->video_id }}"></modal-share-wx>
@endpush

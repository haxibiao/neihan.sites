
@extends('layouts.black')

@section('title')
{{ $movie->name }} - {{ seo_site_name() }}
@stop

@push('seo_og_result')

<meta property="og:type" content="movie" />
<meta property="og:url" content="https://{{ get_domain() }}/movie/{{ $movie->id }}" />
<meta property="og:title" content="{{ $movie->name }}" />
<meta property="og:description" content="{{ $movie->introduction }}" />
<meta property="og:image" content="{{ $movie->cover }}" />
<meta name="weibo: article:create_at" content="{{ $movie->created_at }}" />
<meta name="weibo: article:update_at" content="{{ $movie->updated_at }}" />

@endpush

@section('logo')
<a class="logo" href="/">
    <img src="{{ small_logo() }}" alt="">
</a>
@stop

@section('content')
<div class="player-container">

    <div class="playerBox">
        {{-- <div class="author-info">
            @include('video.parts.author')
        </div> --}}
        <div class="player-basic clearfix">
            <div class="playerArea col-sm-8">
                <div class="h5-player">
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls="" poster="{{ $movie->cover_url }}" preload="auto" autoplay="true">
                            <source src="{{ $movie->play_url }}" type="application/x-mpegURL">
                            </source>
                        </video>
                    </div>
                </div>
                <div class="video-body">
                    <span class="content">
                        {{ $movie->introduction }}
                    </span>
                </div>
                <div class="h5-option">
                    {{-- <div class="comments">
                        <to-comment comment-replies={{ $video->article->count_replies }}></to-comment>
                    </div> --}}
                </div>
            </div>
            <div class="video-right">
                <div class="listArea">
                    {{-- 作者的其他视频 --}}
                    {{-- <authors-video user-id="{{ $video->user_id }}" video-id="{{ $video->id }}" related-page="{{ $data['related_page'] }}"></authors-video> --}}
                </div>
            </div>
        </div>
        <div class="video-title">
            {{ $movie->name }}
            <div class="video-info">
                {{-- @if(!empty($category))
                <a href="/category/{{ $category->id }}" class="category-name">分类: {{ $article->category->name }}</a>
                @endif --}}
			</div>			
			<div class="video-body">
				<span class="content">
					{{ $movie->introduction }}
				</span>
			</div>
        </div>
        <div class="video-relevant">
            {{-- <div class="author-info"> @include('video.parts.author')</div> --}}
        {{-- <authors-video user-id="{{ $video->user_id }}" video-id="{{ $video->id }}"></authors-video> --}}
    </div>
</div>
</div>
<div class="sectionBox">
    <div class="container clearfix">
        <div class="row">
            <div class="col-sm-8">
                {{-- 评论中心 --}}
                {{-- <comments comment-replies={{ $video->article->count_replies }} type="articles" id="{{ $video->article->id }}" author-id="{{ $video->user_id }}"></comments> --}}
            </div>
            <div class="guess-like  hidden-xs">
                {{-- 其他推荐的视频 --}}
                {{-- <authors-video category-id="{{ $video->article->category_id }}" video-id="{{ $video->id }}" related-page="{{ $data['related_page'] }}"></authors-video> --}}
            </div>
        </div>
    </div>
</div>


</div>
<div class="share-module">
    <div class="module-share-h3">分享到....</div>
    <div>@include('video.parts.share', ['subject' => $movie->name, 'url'=>url('/movie/'.$movie->id)])</div>
    <close-share></close-share>
</div>
<div id="pageLayout">

</div>
@stop

@push('scripts')

@if(Auth::check())
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
<modal-share-wx url="{{ url()->full() }}" aid="{{ $movie->id }}"></modal-share-wx>
@endpush
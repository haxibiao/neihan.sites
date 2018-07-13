@extends('layouts.black')

@section('title')
	视频: {{ $video->article->title }} - @if($video->article && $video->article->category){{ $video->article->category->name }}@endif {{ env('APP_NAME') }}
@stop

@push('seo_og_result') 
@if($video->article)
<meta property="og:type" content="{{ $video->article->type }}" />
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
                       <like id="{{ $video->id }}" type="articles" is-login="{{ Auth::check() }}"></like>
                    </div>
                </div>
                <div class="listArea col-sm-4 hidden-xs">
                    <div class="classify">
                        @if($video->article && $video->article->category)
                        <div>
                            <a href="/{{ $video->article->category->name_en }}" class="avatar">
                                <img src="{{ $video->article->category->logo() }}" alt="{{ $video->article->category->name }}">
                            </a>
                            <div class="classify-info">
                                <div>
                                    {{-- 分类 --}}
                                    <a href="/{{ $video->article->category->name_en }}">{{$video->article->category->name}}</a>
                                </div> 
                                {{-- 关注数 --}}
                                <span>{{$video->article->category->count_follows}}人关注</span> 
                                <span>- {{$video->article->category->count_videos}}个视频</span> 
                            </div>
                        </div>
                            
                        <div class="button-vd clearfix">
                            <follow  
                                type="categories" 
                                id="{{ $video->article->category_id }}"   
                                user-id="{{ user_id() }}" 
                                followed="{{ is_follow('categories', $video->article->category_id) }}"
                                size-class="btn-md"
                                >
                            </follow>
                        </div>
                        @endif
                    </div> 
                    <div class="related-video">
                        <ul class="video-list">
                            @foreach($data['related'] as $article) 
                                <li class="video-item hz">
                                    <a href="{{$article->content_url()}}" class="clearfix"> 
                                        <div class="cover">
                                            <img src="{{ $article->primaryImage() }}" alt="{{ $article->title }}">
                                            {{-- 时长 --}}
                                            <span class="duration">@sectominute($article->video->duration)</span>
                                        </div>
                                        <div class="info">
                                            <div class="video-title">{{ $article->title }}</div> 
                                            <span class="amount">
                                                {{-- 播放量 --}}
                                                {{ $article->hits }}次播放
                                            </span>
                                        </div>
                                    </a> 
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="video-info">
                <div class="video-title">
                    {{ $video->article->title }}
                </div> 
                <div class="desc">
                    <span class="upload-time hidden-xs">上传于 {{$video->createdAt()}}</span> 
                    <span class="upload-user">
                        @if($video->user)
                        <a href="/user/{{ $video->user_id }}" class="sub">
                            <img src="{{ $video->user->avatar }}" alt="{{ $video->user->name }}">
                            <span>{{ $video->user->name }}</span>
                        </a>
                        @endif
                        @if( $video->user && !$video->user->isSelf() )
                            <span class="button-vd">
                                <follow 
                                    type="users" 
                                    id="{{ $video->user->id }}" 
                                    user-id="{{ user_id() }}" 
                                    followed="{{ is_follow('users', $video->user->id) }}"
                                    size-class="btn-md"
                                    >
                                </follow>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="sectionBox">
        <div class="container clearfix">
            <div class="row">
                <div class="col-sm-8">  
                    {{-- 评论中心 --}}
                    <comments type="articles" id="{{ $video->article->id }}" author-id="{{ $video->user_id }}"></comments>
                </div>
                <div class="guess-like col-sm-4 hidden-xs">
                    <div class="guessVideo">
                        <h4></h4>
                        <ul class="video-list"> 
                            <div class="top10">
                                
                            </div>
                        </ul>
                    </div>
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
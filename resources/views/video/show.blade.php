@extends('layouts.black')

@section('title')
	视频: {{ $video->title }}
@stop

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
                            <video controls="" poster="{{ $video->cover }}" preload="auto" autoplay="true">
                                <source src="{{ $video->source() }}" type="video/mp4">
                                </source>
                            </video>
                        </div> 
                    </div>
                    <div class="h5-option">
                       <like id="{{ $video->id }}" type="videos" is-login="{{ Auth::check() }}"></like>
                    </div>
                </div>
                <div class="listArea col-sm-4 hidden-xs">
                    <div class="classify">
                        @if($video->category)
                        <div>
                            <a href="/{{ $video->category->name_en }}" class="avatar">
                                <img src="{{ $video->category->logo() }}" alt="{{ $video->category->name }}">
                            </a>
                            <div class="classify-info">
                                <div>
                                    {{-- 分类 --}}
                                    <a href="/{{ $video->category->name_en }}">{{$video->category->name}}</a>
                                </div> 
                                {{-- 关注数 --}}
                                <span>{{$video->category->count_follows}}人关注</span> 
                                <span>- {{$video->category->count_videos}}个视频</span> 
                            </div>
                        </div>
                        <div class="button-vd clearfix">
                            <follow 
                                type="users" 
                                id="{{ $video->user->id }}" 
                                user-id="{{ user_id() }}" 
                                followed="{{ is_follow('users', $video->user->id) }}"
                                size-class="btn-md"
                                >
                            </follow>
                        </div>
                        @endif
                    </div>
                    <div class="related-video">
                        <ul class="video-list">
                            @foreach($data['related'] as $video_related) 
                                <li class="video-item hz">
                                    <a href="/video/{{$video_related->id}}" class="clearfix">
                                        <div class="cover">
                                            <img src="{{ $video_related->cover() }}" alt="{{ $video_related->title }}">
                                            {{-- 时长 --}}
                                            <span class="duration">@sectominute($video_related->duration)</span>
                                        </div>
                                        <div class="info">
                                            <div class="video-title">{{ $video_related->title }}</div>
                                            <span class="amount">
                                                {{-- 播放量 --}}
                                                {{ $video_related->hits }}次播放
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
                    {{ $video->title }}
                </div> 
                <div class="desc">
                    <span class="upload-time hidden-xs">上传于 {{$video->createdAt()}}</span> 
                    <span class="upload-user">
                        <a href="/user/{{ $video->user->id }}" class="sub">
                            <img src="{{ $video->user->avatar }}" alt="{{ $video->user->name }}">
                            <span>{{ $video->user->name }}</span>
                        </a>
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
                    <comments type="videos" id="{{ $video->id }}" author-id="{{ $video->user_id }}"></comments>
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
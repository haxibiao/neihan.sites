@extends('layouts.black')

@section('title')
	视频: {{ $video->title }}
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
                        <div>
                            <a href="https://ainicheng.com/yingxionglianmengnicheng" class="avatar">
                                <img src="https://ainicheng.com/storage/img/1832.small.jpg.logo.jpg" alt="英雄联盟">
                            </a>
                            <div class="classify-info">
                                <div>
                                    {{-- 分类 --}}
                                    <a href="https://ainicheng.com/yingxionglianmengnicheng">英雄联盟</a>
                                </div>
                                {{-- 关注数 --}}
                                <span>83人关注</span>
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
                    </div>
                    <div class="related-video">
                        <ul class="video-list">
                            @foreach($data['related'] as $video) 
                                <li class="video-item hz">
                                    <a href="/video/{{$video->id}}" class="clearfix">
                                        <div class="cover">
                                            <img src="{{ $video->cover() }}" alt="{{ $video->title }}">
                                            {{-- 时长 --}}
                                            <span class="duration"> 0{{ rand(1,9) }}:{{ rand(10,59) }}</span>
                                        </div>
                                        <div class="info">
                                            <div class="video-title">{{ $video->title }}</div>
                                            <span class="amount">
                                                {{-- 播放量 --}}
                                                {{ rand(100,1000) }}次播放
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
                    <span class="upload-time hidden-xs">上传于 2018-04-08</span>
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
                        <h4>推荐阅读</h4>
                        <ul class="video-list">
                            <div class="top10">
                                @include('article.parts.connections')
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

    
    {{-- 遗留代码 --}}
    <div style="display: none">
          <ol class="breadcrumb">
            <li><a href="/">{{ config('app.name') }}</a></li>
            @if($video->category)
            <li><a href="/$video->category->name_en">{{ $video->category->name}}</a></li>
            @endif
            <li class="active">{{ $video->title }}</li>
          </ol>

        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">            
                    @if(checkEditor())
                    <div class="pull-right">
                        <a class="btn btn-success" href="/video/{{ $video->id }}/edit" role="button">
                            编辑
                        </a>
                    </div>
                    @endif
                    <h3 class="panel-title" style="line-height: 30px">
                        {{  $video->title }}
                    </h3>
                </div>
                <div class="panel-body">
                    <p>
                        上传用户: <a href="/user/{{ $video->user->id }}">{{ $video->user->name }}</a>
                    </p>
                    <p>
                        最后修改: {{ $video->updatedAt() }}
                    </p>
                    @if(!empty($video->introduction))
                    <p class="lead">
                        视频简介: {{ $video->introduction }}
                    </p>
                    @endif
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls="" poster="{{ $video->cover }}" preload="auto" autoplay="true">
                            <source src="{{ $video->source() }}" type="video/mp4">
                            </source>
                        </video>
                    </div>

                    @if(Auth::check())
                    <div class="row top10">
                        <div>
                           <like id="{{ $video->id }}" type="videos" is-login="{{ Auth::check() }}"></like>
                        </div>

                        <div class="top10"> 
                            {{-- 评论中心 --}}
                            <comments type="videos" id="{{ $video->id }}" author-id="{{ $video->user_id }}"></comments>
                        </div>
                    </div>
                    @endif

                    
                    {{-- TODO: 暂时隐藏  .... 编辑推荐关联去看的文章  --}}
                   {{--  <div class="top10">
                        @include('article.parts.connections')
                    </div> --}}

                    @if(!$video->articles->isEmpty())
                    <div class="top10">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">相关文章</h3>
                            </div>
                            <div class="panel-body">
                                @foreach($video->articles as $article) 
                                    @include('article.parts.article_item')
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-md-4">
            @foreach($data['related'] as $video)
            <div class="media">
                <a class="pull-left" href="/video/{{$video->id}}">
                    <img class="media-object" src="{{ $video->cover() }}" alt="{{ $video->title }}">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">{{ $video->title }}</h4>
                    <p>{{ $video->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    

</div>
@stop

@push('scripts')
    @include('parts.js_for_app')
@endpush
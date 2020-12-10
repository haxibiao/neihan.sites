@extends('layouts.movie')

@section('title') 
{{ $movie->name }} - 
@endsection

@push('head-styles')
<link rel="stylesheet" href="{{ mix('css/movie/play.css') }}">
@endpush

@section('content')
<div class="app-player">
    <div class="container-xl">
        @if(checkUser())
            <movie-player :movie="{{ $movie }}"
            count="{{ count($movie->data) }}" 
            token="{{ getUser()->api_token }}"
            />
        @else
        <movie-player :movie="{{ $movie }}"
            count="{{ count($movie->data) }}"
            />
        @endif()
    </div>
</div>
<div class="container-xl">
    <div class="video-main row">
        <div class="main-left col-lg-9">
            <div class="video-info">
                <div id="media_module" class="clearfix report-wrap-module">
                    <a href="/movie/{{ $movie->id }}" target="_blank" class="video-cover">
                        <img src="{{ $movie->cover_url }}" alt="">
                    </a>
                    <div class="video-right">
                        <a href="/movie/{{ $movie->id }}" target="_blank" title="{{ $movie->name }}" class="video-title">{{ $movie->name }}
                        </a>
                        <div class="video-count text-ellipsis">
                            118.5万播放&nbsp;&nbsp;·&nbsp;&nbsp;1.2万评论&nbsp;&nbsp;·&nbsp;&nbsp;32.8万追剧
                        </div>
                        <div class="pub-wrapper clearfix">
                            <a href="/movie" target="_blank" class="home-link">电视剧</a>
                            <span class="pub-info">连载中, 13话</span>
                        </div>
                        <a href="/home" target="_blank" class="video-desc webkit-ellipsis">
                            <span class="absolute">{{ str_limit($movie->introduction,200) }} </span>
                            <span>{{ $movie->introduction }}</span>
                            {{-- <i style="">展开</i> --}}
                        </a>
                        <div class="video-rating">
                            <h4 class="score">9.8</h4>
                            <p>857人评分</p>
                        </div>
                        {{-- <div class="video-tool-bar clearfix">
                            <div report-id="click_review_publish" class="btn-rating">
                                <ul class="star-wrapper clearfix">
                                    <li>
                                        <i class="iconfont icon-star-empty"></i>
                                    </li>
                                    <li>
                                        <i class="iconfont icon-star-empty"></i>
                                    </li>
                                    <li>
                                        <i class="iconfont icon-star-empty"></i>
                                    </li>
                                    <li>
                                        <i class="iconfont icon-star-empty"></i>
                                    </li>
                                    <li>
                                        <i class="iconfont icon-star-empty"></i>
                                    </li>
                                </ul>
                                <span>点评</span>
                            </div>
                            <div report-id="click_follow" class="btn-follow">
                                <i class="iconfont icon-follow"></i>
                                <span>追剧</span>
                                <div class="bangumi-options clearfix">
                                    <ul class="opt-list">
                                        <li class="">标记为 想看</li>
                                        <li class="disabled">标记为 在看</li>
                                        <li class="">标记为 已看</li>
                                        <li>取消追番</li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="video-comment">
                <comment-module movie-id={{ $movie->id }}/>
            </div>
        </div>
        <div class="side-right col-lg-3">
            <div class="recommend-module">
                <div class="rc-title">
                    相关{{ $movie->region_name }}
                </div>
                <div class="rc-list row">
					@foreach($more as $movie)
                    <div class="rc-item col-lg-12 col-sm-6 clearfix">
                        <a href="/movie/{{ $movie->id }}" target='_blank' class="cover-wrapper">
                            <div class="common-lazy-img">
                                <img src="{{ $movie->cover_url }}" alt="{{ $movie->name }}">
                            </div>
                            <div class="video-mask"></div>
                            <div class="duration">54:29:05</div>
                        </a>
                        <div class="info-wrapper">
                            <a href="/home" target='_blank' class="video-title webkit-ellipsis" title="{{ $movie->name }}">
                                {{ $movie->name }}
                            </a>
                            <div class="video-count">{{ rand(1,99) }}.{{ rand(0,9) }}万播放&nbsp;&nbsp;·&nbsp;&nbsp;1万评论</div>
                        </div>
					</div>
					@endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('foot-scripts')
<script src="{{ asset('js/hls.js') }}" defer></script>
<script src="{{ mix('js/movie/play.js') }}" defer></script>
@endpush

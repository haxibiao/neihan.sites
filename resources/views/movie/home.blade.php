@php
$movieSection = array_fill(0, 4, '最新电视剧');
$movieList = array_fill(0, 6, 'movieObject');
$movieTitle = array_fill(0, 12, '我和我的家乡');
$videoList = array_fill(0, 4, 'video_list');
@endphp

@extends('layouts.movie')

@push('head-styles')
<link rel="stylesheet" href="{{ mix('css/movie/home.css') }}">
@endpush
{{-- @push('head-scripts')
<script src="{{ asset('js/vue-components.js') }}" defer></script>
@endpush --}}

@section('top')
<div class="home-top-bg"></div>
@endsection
@section('content')
<div class="hot-video-box">
    <div class="container-xl">
        <div class="row">
            @include('movie.parts.hotMovies')
        </div>
    </div>
</div>
<div class="movies-panel-container">
    <div class="container-xl">
        <div class="row">
            @foreach($data as $cate => $movies)
            <div class="movies-panel">
                <div class="panel-head clearfix">
                    <div class="title">
                        {{$cate}}
                    </div>
                    <a class="more" href="/play">更多<i class="iconfont icon-arrow-right"></i></a>
                </div>
                <div class="movie-list">
                    @foreach($movies as $movie)
                    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
                        @include('movie.parts.movie_item')
                    </div>
                    @endforeach
                </div>
                <ul class="movie-list_title col-row clearfix">
                    @foreach($movieTitle as $item)
                    <li class="col-lg-2 col-md-3 col-xs-6 padding-0">
                        <a class="movie-title text-ellipsis" href="/play" title={{$item}}>
                            <span class="badge">{{$loop->index + 1}}</span>{{$item}}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</div>
<div class="video-list-container">
    <div class="container-xl">
        <div class="row">
            @foreach($data as $cate => $movies)
            <div class="col-lg-3 col-sm-6 col-xs-12">
                @include('movie.parts.video_list_item', ['cate'=>$cate])
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('foot-scripts')
<script type="text/javascript" src="{{ mix('js/movie/home.js') }}"></script>
@endpush

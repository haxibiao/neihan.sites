@extends('layouts.app')

@section('title')
	视频列表
@stop

@section('content')
<div class="container">
	@include('video.parts.video_navs')
    @include('video.parts.new_video')
    <video-list></video-list>
    {{--  @include('video.parts.hot_category_video')
     @include('video.parts.hot_category_video')
     @include('video.parts.hot_category_video')
     @include('video.parts.hot_category_video') --}}
</div>
@stop

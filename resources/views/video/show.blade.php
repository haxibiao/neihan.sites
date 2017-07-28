@extends('layouts.app')

@section('title')
	视频: {{ $video->title }} - 
@stop

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                {{  $video->title }}
            </h3>
        </div>
        <div class="panel-body">
            <p>
                用户:{{ $video->user->name }}
            </p>
            <p>
                最后修改时间: {{ diffForHumansCN($video->updated_at) }}
            </p>
            <div class="embed-responsive embed-responsive-16by9">
                <video autoplay="true" controls="" poster="{{ get_img($video->cover) }}" preload="auto">
                    <source src="{{ get_img($video->path) }}" type="video/mp4">
                    </source>
                </video>
            </div>
        </div>
        <div class="panel-footer">
            <a class="btn btn-default" href="/video" role="button">
                返回列表
            </a>
        </div>
    </div>
</div>
@stop

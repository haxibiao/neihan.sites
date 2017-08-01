@extends('layouts.app')

@section('title')
	视频: {{ $video->title }} - 
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li><a href="/video">视频</a></li>
        <li class="active">{{ $video->title }}</li>
      </ol>

    <div class="panel panel-default">
        <div class="panel-heading">            
            @if(Auth::check() && Auth::user()->is_editor)
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
                最后修改: {{ diffForHumansCN($video->updated_at) }}
            </p>
            <p class="lead">
                视频简介: {{ $video->introduction }}
            </p>
            <div class="embed-responsive embed-responsive-16by9">
                <video autoplay="true" controls="" poster="{{ get_img($video->cover) }}" preload="auto">
                    <source src="{{ get_img($video->path) }}" type="video/mp4">
                    </source>
                </video>
            </div>

            <div class="top10">
                @include('article.parts.connections')
            </div>
        </div>
    </div>
</div>
@stop

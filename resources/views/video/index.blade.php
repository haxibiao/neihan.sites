@extends('layouts.app')

@section('title')
	视频列表
@stop

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading" style="min-height: 50px">
            @if(Auth::check() && Auth::user()->is_editor)
            <div class="pull-right">
                <a class="btn btn-default" href="/video/create" role="button">
                    添加视频
                </a>
            </div>
            @endif
            <h3 class="panel-title">
                视频列表
            </h3>
        </div>
        <div class="panel-body">
            @foreach($videos as $video)
            <div class="media">
                <a class="pull-left" href="/video/{{ $video->id }}">
                    <img alt="{{ $video->title }}" class="media-object img-responsive" 
                    	src="{{ get_img($video->cover) }}" style="max-width: 200px">
                    </img>
                </a>
                <div class="media-body">
                    @if(Auth::user()->is_editor)
                    <div class="pull-right">
                        <a class="btn btn-default" href="/video/{{ $video->id }}/edit" role="button">
                            编辑
                        </a>
                    </div>
                    @endif
                    <h4 class="media-heading">
                        {{ $video->title }}
                    </h4>
                </div>
            </div>
            @endforeach
            <p>
                {{ $videos->render() }}
            </p>
        </div>
    </div>
</div>
@stop

@extends('layouts.app')

@section('title')
	视频列表
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li class="active">视频</li>
      </ol>
    <div class="panel panel-default">
        <div class="panel-heading">
            @if(Auth::check() && Auth::user()->is_editor)
            <div class="pull-right">
                <a class="btn btn-primary" href="/video/create" role="button">
                    添加视频
                </a>
            </div>
            @endif
            <h3 class="panel-title" style="line-height: 30px">
                视频列表
            </h3>
        </div>
        <div class="panel-body">
            @foreach($videos as $video)
            <div class="media">
                <a class="pull-left" href="/video/{{ $video->id }}">
                    <img alt="{{ $video->title }}" class="media-object" 
                    	src="{{ get_img($video->cover) }}">
                    </img>
                </a>
                <div class="media-body">
                    @if(Auth::check() && Auth::user()->is_editor)
                    <div class="pull-right">
                      {!! Form::open(['method' => 'delete', 'route' => ['video.destroy', $video->id], 'class' => 'form-horizontal pull-left right10']) !!}
                        {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}                
                      {!! Form::close() !!}
                        <a class="btn btn-success" href="/video/{{ $video->id }}/edit" role="button">
                            编辑
                        </a>
                    </div>
                    @endif
                    <h4 class="media-heading">
                        {{ $video->title }}
                    </h4>
                    <p>
                        上传用户:　<a href="/user/{{ $video->user->id }}">{{ $video->user->name }}</a>
                    </p>
                    <p>
                        最后更新: {{ diffForHumansCN($video->updated_at) }}
                    </p>
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

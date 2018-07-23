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
            @if(checkEditor())
            <div class="pull-right">
                <a class="btn btn-primary" href="/video/create" role="button">
                    添加视频
                </a>
            </div>
            @endif
            <h3 class="panel-title" style="line-height: 30px">
                视频列表
                <basic-search api="/video/list?q="></basic-search>
            </h3>
        </div>
        <div class="panel-body">
            @foreach($videos as $video)
                @if( !empty($video->article) )
                    <div class="media">
                        <a class="pull-left" href="/video/{{ $video->id }}">
                            <img alt="{{ $video->article->title }}" class="img img-thumbnail img-responsive" 
                                src="{{ $video->article->cover() }}" style="max-width: 300px">
                            </img>
                        </a>
                        <div class="media-body">
                            @if(checkEditor())
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
                                <a href="/video/{{ $video->id }}">
                                {{ $video->article->title }}
                                </a>
                            </h4>
                            <p>
                                主分类: {{ !empty($video->article->category) ? $video->article->category->name : '暂无(该视频未发布)' }}
                            </p>
                            @if($video->user)
                            <p>
                                上传用户:　<a href="/user/{{ $video->user_id }}">{{ $video->user->name }}</a>
                            </p>
                            @endif
                            <p>
                                最后更新: {{ $video->updatedAt() }}
                            </p>
                            <p>
                                @if(count($video->article->covers()) == 8)
                                    <span class="label label-success">截图已完成</span>
                                @else
                                    <span class="label label-default">截图ing...</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            @endforeach
            <p>
                {{ $videos->render() }}
            </p>
        </div>
    </div>
</div>
@stop

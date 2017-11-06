@extends('layouts.app')

@section('title')
	 图片列表
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li class="active"> 图片</li>
      </ol>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title" style="line-height: 30px">
                 图片列表
            </h3>
        </div>
        <div class="panel-body">
            @foreach($images as $image)
            <div class="media">
                <a class="pull-left" href="/image/{{ $image->id }}">
                    <img alt="{{ $image->title }}" class="media-object" 
                    	src="{{ get_img($image->path_small) }}">
                    </img>
                </a>
                <div class="media-body">
                    @if(Auth::check() && Auth::user()->is_editor)
                    <div class="pull-right">
                      {!! Form::open(['method' => 'delete', 'route' => ['image.destroy', $image->id], 'class' => 'form-horizontal pull-left right10']) !!}
                        {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}                
                      {!! Form::close() !!}
                        <a class="btn btn-success" href="/image/{{ $image->id }}/edit" role="button">
                            编辑
                        </a>
                    </div>
                    @endif
                    <h4 class="media-heading">
                        {{ $image->title }}
                    </h4>
                    <p>
                        上传用户:　<a href="/user/{{ $image->user->id }}">{{ $image->user->name }}</a>
                    </p>
                    <p>
                        最后更新: {{ diffForHumansCN($image->updated_at) }}
                    </p>
                </div>
            </div>
            @endforeach
            <p>
                {{ $images->render() }}
            </p>
        </div>
    </div>
</div>
@stop

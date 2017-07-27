@extends('layouts.app')

@section('title')
	编辑视频 - 
@stop

@section('content')
<div class="container">
    <div class="panel panel-defau">
        <div class="panel-heading">
            <h3 class="panel-title">
                编辑视频
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                {!! Form::open(['method' => 'PUT', 'route' => ['video.update', $video->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    {!! Form::label('title', '视频标题') !!}
					        {!! Form::text('title', $video->title, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('title') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('introduction') ? ' has-error' : '' }}">
                    {!! Form::label('introduction', '视频介绍(非必填)') !!}
					        {!! Form::textarea('introduction', $video->introduction, ['class' => 'form-control']) !!}
                    <small class="text-danger">
                        {{ $errors->first('introduction') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('video') ? ' has-error' : '' }}">
                    {!! Form::label('video', '视频文件') !!}
					        {!! Form::file('video') !!}
                    <p class="help-block">
                        (目前只支持mp4格式，其他的需要先转码)
                    </p>
                    <small class="text-danger">
                        {{ $errors->first('video') }}
                    </small>
                </div>
                <div class="btn-group pull-right">
                    {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
					        {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@extends('layouts.app')

@section('title')
	添加视频
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li><a href="/video">视频</a></li>
      </ol>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                添加视频
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                {!! Form::open(['method' => 'POST', 'route' => 'video.store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    {!! Form::label('title', '视频标题(非必填,默认取上传的mp4文件名)') !!}
                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                    <small class="text-danger">
                        {{ $errors->first('title') }}
                    </small>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('categories') ? ' has-error' : '' }}">
                            {!! Form::label('categories', '专题(直接收录！)') !!}
                            <category-select></category-select>
                            <small class="text-danger">{{ $errors->first('categories') }}</small>
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    {!! Form::label('description', '视频介绍(必填)') !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'required'=>true]) !!}
                    <small class="text-danger">
                        {{ $errors->first('description') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('video') ? ' has-error' : '' }}">
                    {!! Form::label('video', '视频文件') !!}
                            {!! Form::file('video') !!}
                    <p class="help-block">
                        (目前只支持mp4格式)
                    </p>
                    <small class="text-danger">
                        {{ $errors->first('video') }}
                    </small>
                </div>
                <div class="btn-group pull-right">
			        {!! Form::submit("提交", ['class' => 'btn btn-success']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="panel-footer">
            <a class="btn btn-default" href="/video" role="button">返回列表</a>
        </div>
    </div>
</div>
@stop

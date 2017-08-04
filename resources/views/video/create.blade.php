@extends('layouts.app')

@section('title')
	添加视频 - 
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
                <div class="form-group{{ $errors->has('introduction') ? ' has-error' : '' }}">
                    {!! Form::label('introduction', '视频介绍(非必填)') !!}
                            {!! Form::textarea('introduction', null, ['class' => 'form-control']) !!}
                    <small class="text-danger">
                        {{ $errors->first('introduction') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('path') ? ' has-error' : '' }}">
                    {!! Form::label('path', '视频地址(提供了cdn地址，下面视频文件可以无需上传)') !!}
                    {!! Form::text('path', null, ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('path') }}</small>
                </div>
                <div class="form-group{{ $errors->has('video') ? ' has-error' : '' }}">
                    {!! Form::label('video', '视频文件') !!}
                            {!! Form::file('video') !!}
                    <p class="help-block">
                        (目前只支持mp4格式，其他的需要先转码,　如果提供了cdn地址，这里就无需再上传了)
                    </p>
                    <small class="text-danger">
                        {{ $errors->first('video') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('adstime') ? ' has-error' : '' }}">
                    {!! Form::label('adstime', '广告时间(非必填，如果有就填写，方便后续脚本清理广告)') !!}
                    {!! Form::text('adstime', null, ['class' => 'form-control', 'placeholder'=>'(比如: ５秒之前,输入 <5s 4分33秒之后，输入: >4:33 中间时段： 2:20-2:25，多个时段，空格隔开)']) !!}
                    <small class="text-danger">{{ $errors->first('adstime') }}</small>
                </div>
                <div class="btn-group pull-right">
                    {!! Form::hidden('user_id', Auth::user()->id) !!}
                    {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
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

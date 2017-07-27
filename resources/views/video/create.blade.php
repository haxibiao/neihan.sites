@extends('layouts.app')

@section('title')
	添加视频 - 
@stop

@section('content')
<div class="container">
    <div class="panel panel-defau">
        <div class="panel-heading">
            <h3 class="panel-title">
                添加视频
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                {!! Form::open(['method' => 'POST', 'route' => 'video.store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    {!! Form::label('title', '视频标题') !!}
					        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('title') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                    {!! Form::label('category', '视频分类') !!}
                    {!! Form::select('category',['开发培训'=>'开发培训','seo培训'=>'seo培训','编辑培训'=>'编辑培训'], null, ['id' => 'category', 'class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">{{ $errors->first('category') }}</small>
                </div>
                <div class="form-group{{ $errors->has('cdn_url') ? ' has-error' : '' }}">
                    {!! Form::label('cdn_url', 'CDN播放地址') !!}
					        {!! Form::text('cdn_url', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('cdn_url') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('screenshot') ? ' has-error' : '' }}">
                    {!! Form::label('screenshot', '视频截图') !!}
					        {!! Form::file('screenshot', ['required' => 'required']) !!}
                    <p class="help-block">
                        这是本地用ffmpeg截取第30秒的视频截图
                    </p>
                    <small class="text-danger">
                        {{ $errors->first('screenshot') }}
                    </small>
                </div>
                <div class="btn-group pull-right">
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

@extends('layouts.app')

@section('title')
	编辑视频 - 
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li><a href="/video">视频</a></li>
        <li><a href="/video/{{ $video->id }}">{{ $video->title }}</a></li>
      </ol>
    <div class="panel panel-defau">
        <div class="panel-heading">
            <h3 class="panel-title">
                编辑视频
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-md-10">
                {!! Form::open(['method' => 'PUT', 'route' => ['video.update', $video->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    {!! Form::label('title', '视频标题(非必填)') !!}
					        {!! Form::text('title', $video->title, ['class' => 'form-control']) !!}
                    <small class="text-danger">
                        {{ $errors->first('title') }}
                    </small>
                </div>
             {{--    <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                    {!! Form::label('category_id', '视频分类') !!}
                    {!! Form::select('category_id', $data['video_categories'], $video->category_id, ['id' => 'category_id', 'class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">{{ $errors->first('category_id') }}</small>
                </div> --}}
                @editor
                <div class="row">            
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('category_ids') ? ' has-error' : '' }}">
                            {!! Form::label('category_ids', '专题') !!}
                            <category-select categories="{{ json_encode($video->categories->pluck('name','id')) }}"></category-select>
                            <small class="text-danger">{{ $errors->first('category_ids') }}</small>
                        </div>
                    </div>
                </div>
                @endeditor
                <div class="form-group{{ $errors->has('introduction') ? ' has-error' : '' }}">
                    {!! Form::label('introduction', '视频介绍(非必填)') !!}
					        {!! Form::textarea('introduction', $video->introduction, ['class' => 'form-control']) !!}
                    <small class="text-danger">
                        {{ $errors->first('introduction') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('path') ? ' has-error' : '' }}">
                    {!! Form::label('path', '视频地址') !!}
                    {!! Form::text('path', $video->path, ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('path') }}</small>
                </div>
                {{-- <div class="form-group{{ $errors->has('hash') ? ' has-error' : '' }}">
                    {!! Form::label('hash', '文件md5 hash') !!}
                    {!! Form::text('hash',$video->hash, ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('hash') }}</small>
                </div> --}}
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
                    {!! Form::text('adstime', $video->adstime, ['class' => 'form-control', 'placeholder' => '比如: ５秒之前,输入 <5s 4分33秒之后，输入: >4:33 中间时段： 2:20-2:25，多个时段，空格隔开']) !!}
                    <small class="text-danger">{{ $errors->first('adstime') }}</small>
                </div>
                <div class="btn-group pull-right">
                    {!! Form::hidden('user_id', Auth::user()->id) !!}
                    {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
			        {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
                </div>
                {!! Form::close() !!}
            </div>

            <div class="col-md-2">
                @include('video.parts.add_panel')
            </div>
        </div>
    </div>
</div>
@stop

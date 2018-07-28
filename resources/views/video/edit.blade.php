@extends('layouts.app')

@section('title')
	编辑视频 - {{ $video->article->title }}
@stop
@php
    //编辑成功返回之前的页面
    session()->put('url.intended', request()->headers->get('referer'));
@endphp

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
            <div class="col-md-10 col-md-offset-1">
                {!! Form::open(['method' => 'PUT', 'route' => ['video.update', $video->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    {!! Form::label('title', '标题(非必填)') !!}
					{!! Form::text('title', $video->article->title, ['class' => 'form-control']) !!}
                    <small class="text-danger">
                        {{ $errors->first('title') }}
                    </small>
                </div>
            
            
                <div class="row">    
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('categories') ? ' has-error' : '' }}">
                            {!! Form::label('categories', '专题') !!}
                            <category-select 
                                categories="{{ json_encode($video->article->categories->pluck('name','id')) }}">        
                            </category-select>
                            <small class="text-danger">{{ $errors->first('categories') }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                    {!! Form::label('descri', '正文(必填)') !!}
					{!! Form::textarea('body', $video->article->body, ['class' => 'form-control', 'required'=>true]) !!}
                    <small class="text-danger">
                        {{ $errors->first('body') }}
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

                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">截图</h3>
                        </div>
                        <div class="panel-body">
                            @php   
                                $thumbIndex = 0; 
                            @endphp
                            @foreach($data['thumbnails'] as $thumbnail)
                            @php
                                $thumbIndex++;
                            @endphp
                            <div class="col-xs-6 col-md-3 {{ $errors->has('thumbnail') ? ' has-error' : '' }}">
                                <label for="thumbnail{{ $thumbIndex }}">
                                    <img src="{{ $thumbnail }}" class="img img-responsive">
                                </label>

                                {!! str_replace('>','id="'.'thumbnail'.$thumbIndex.'">', Form::radio('thumbnail', $thumbnail)) !!}
                                <label for="thumbnail{{ $thumbIndex }}">
                                    选取
                                </label>
                                <small class="text-danger">{{ $errors->first('thumbnail') }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>      
                </div>                 

                <div class="btn-group pull-right">
			        {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
@stop

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
            <div class="col-md-10 col-md-offset-1">
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

                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">截图</h3>
                        </div>
                        <div class="panel-body">
                            @php   
                                $thumbIndex = 0; 
                            @endphp
                            @foreach($data['thumbnail'] as $thumbnail)
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
                    {!! Form::hidden('user_id', Auth::user()->id) !!}
                    {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
			        {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
@stop

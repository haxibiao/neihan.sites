@extends('layouts.app')

@section('title')
     编辑音乐
@stop

@section('content')
     <div class="container">
	      <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                创建音乐
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                {!! Form::open(['method' => 'PUT', 'route' => ['music.update',$music->id], 'class' => 'form-horizontal','enctype' => "multipart/form-data"]) !!}

                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    {!! Form::label('title', '创建的音乐名') !!}
            {!! Form::text('title', $music->title, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('title') }}
                    </small>
                </div>

                <div class="form-group{{ $errors->has('article_id') ? ' has-error' : '' }}">
                    {!! Form::label('article_id', '想关联的文章id(只允许填写数字,可以不填写)') !!}
                    {!! Form::text('article_id', $music->article_id, ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('article_id') }}</small>
                </div>

               <div class="form-group{{ $errors->has('music') ? ' has-error' : '' }}">
                   {!! Form::label('music', '在这里上传') !!}
                   {!! Form::file('music', []) !!}
                   <p class="help-block">上传最大不超过2m</p>
                   <small class="text-danger">{{ $errors->first('music') }}</small>
               </div>

              {!! Form::hidden('user_id', Auth::id()) !!}
                <div class="btn-group pull-right">
                    {!! Form::submit("提交", ['class' => 'btn btn-success']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
     </div>
@stop
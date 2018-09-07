@extends('layouts.app')

@section('content')
	<div class="container"> 
        <ol class="breadcrumb">
            <li>
                <a href="/admin">后台管理</a>
            </li>
            <li class="active">用户编辑</li>
        </ol>

		{!! Form::open(['method' => 'put', 'route' => ['user.update', $user->id], 'class' => 'form-horizontal']) !!}
			
		    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
		        {!! Form::label('name', '用户昵称') !!}
		        {!! Form::text('name', $user->name, ['class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('name') }}</small>
		    </div>

		    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		        {!! Form::label('email', 'email') !!}
		        {!! Form::text('email',$user->email, ['class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('email') }}</small>
		    </div>

		    <div class="form-group{{ $errors->has('qq') ? ' has-error' : '' }}">
		        {!! Form::label('qq', 'qq') !!}
		        {!! Form::text('qq',$user->qq, ['class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('qq') }}</small>
		    </div>

		    <div class="form-group{{ $errors->has('introduction') ? ' has-error' : '' }}">
		        {!! Form::label('introduction', '介绍') !!}
		        {!! Form::textarea('introduction',$user->introduction, ['class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('introduction') }}</small>
		    </div>		    

			@if(Auth::user()->is_admin)
		    <div class="form-group{{ $errors->has('is_editor') ? ' has-error' : '' }}">
		        {!! Form::label('is_editor', '是编辑?') !!}
		        {!! Form::select('is_editor',['0'=>'不是', '1'=>'是'], $user->is_editor, ['id' => 'is_editor', 'class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('is_editor') }}</small>
		    </div>
		    <div class="form-group{{ $errors->has('is_signed') ? ' has-error' : '' }}">
		        {!! Form::label('is_signed', '是签约作者?') !!}
		        {!! Form::select('is_signed',['0'=>'不是', '1'=>'是'], $user->is_signed, ['id' => 'is_signed', 'class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('is_signed') }}</small>
		    </div>
		    @endif
		
		    <div class="btn-group pull-right">
		        {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
		        {!! Form::submit("提交", ['class' => 'btn btn-success']) !!}
		    </div>
		
		{!! Form::close() !!}
	</div>
@stop
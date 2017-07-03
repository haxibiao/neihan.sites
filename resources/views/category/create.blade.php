@extends('layouts.app')

@section('title')添加分类@stop

@section('keywords')
	添加分类
@stop

@section('description')
	添加分类
@stop

@section('content')
	<div class="container">
		<div class="col-md-6">
			{!! Form::open(['method' => 'POST', 'route' => 'category.store', 'class' => 'form-horizontal']) !!}
	
			    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
			        {!! Form::label('name', '分类名称') !!}
			        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
			        <small class="text-danger">{{ $errors->first('name') }}</small>
			    </div>

			    <div class="form-group{{ $errors->has('name_en') ? ' has-error' : '' }}">
			        {!! Form::label('name_en', '分类英文名') !!}
			        {!! Form::text('name_en', null, ['class' => 'form-control', 'required' => 'required']) !!}
			        <small class="text-danger">{{ $errors->first('name_en') }}</small>
			    </div>

			    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
			        {!! Form::label('description', '分类描述') !!}
			        {!! Form::textarea('description', null, ['class' => 'form-control', 'required' => 'required']) !!}
			        <small class="text-danger">{{ $errors->first('description') }}</small>
			    </div>

			    <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
			        {!! Form::label('logo', '分类图标') !!}
			        {!! Form::file('logo') !!}
			        <p class="help-block">请选择少于2M的图片，格式必须是jpg,png</p>
			        <small class="text-danger">{{ $errors->first('logo') }}</small>
			    </div>
			
			    <div class="btn-group pull-right">
			        {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
			        {!! Form::submit("提交", ['class' => 'btn btn-success']) !!}
			    </div>

			    {!! Form::hidden('user_id', $user->id) !!}
			
			{!! Form::close() !!}
		</div>
	</div>
@stop
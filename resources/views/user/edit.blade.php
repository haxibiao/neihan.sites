@extends('layouts.app')

@section('content')
	<div class="container">      
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

		    <div class="form-group{{ $errors->has('is_seoer') ? ' has-error' : '' }}">
		        {!! Form::label('is_seoer', '是SEO人员?') !!}
		        {!! Form::select('is_seoer',['0'=>'不是', '1'=>'是'], $user->is_seoer, ['id' => 'is_seoer', 'class' => 'form-control', 'required' => 'required']) !!}
		        <small class="text-danger">{{ $errors->first('is_seoer') }}</small>
		    </div>
		    @endif

		    @if(Auth::user()->is_seoer)
		    <div class="form-group{{ $errors->has('seo_meta') ? ' has-error' : '' }}">
		        {!! Form::label('seo_meta', 'SEO站点验证meta') !!}
		        {!! Form::textarea('seo_meta',$user->seo_meta, ['class' => 'form-control']) !!}
		        <small class="text-danger">{{ $errors->first('seo_meta') }}</small>
		    </div>
		    <div class="form-group{{ $errors->has('seo_push') ? ' has-error' : '' }}">
		        {!! Form::label('seo_push', 'SEO站点push代码(可以多个搜索平台的一起输入,换行分开即可)') !!}
		        {!! Form::textarea('seo_push',$user->seo_push, ['class' => 'form-control']) !!}
		        <small class="text-danger">{{ $errors->first('seo_push') }}</small>
		    </div>
		    <div class="form-group{{ $errors->has('seo_tj') ? ' has-error' : '' }}">
		        {!! Form::label('seo_tj', 'SEO站点统计代码(同上)') !!}
		        {!! Form::textarea('seo_tj',$user->seo_tj, ['class' => 'form-control']) !!}
		        <small class="text-danger">{{ $errors->first('seo_tj') }}</small>
		    </div>
		    @endif
		
		    <div class="btn-group pull-right">
		        {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
		        {!! Form::submit("提交", ['class' => 'btn btn-success']) !!}
		    </div>
		
		{!! Form::close() !!}
	</div>
@stop
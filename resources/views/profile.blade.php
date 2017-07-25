@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">修改个人资料</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					{!! Form::open(['method' => 'POST', 'route' => ['user.update', Auth::user()->id], 'class' => 'form-horizontal', 'enctype' => "multipart/form-data"]) !!}
		            {!! Form::hidden('_method', 'put') !!}
		            <div class="form-group{{ $errors->has('qq') ? ' has-error' : '' }}">
		                {!! Form::label('qq', 'QQ') !!}
		                {!! Form::text('qq', $user->qq, ['class' => 'form-control', 'required' => 'required']) !!}
		                <small class="text-danger">{{ $errors->first('qq') }}</small>
		            </div>
		            <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
		                {!! Form::label('avatar', '头像') !!}
		                {!! Form::file('avatar') !!}
		                <p class="help-block">建议使用本人真实头像</p>
		                <small class="text-danger">{{ $errors->first('avatar') }}</small>
		            </div>
		            <div class="form-group{{ $errors->has('introduction') ? ' has-error' : '' }}">
		                {!! Form::label('introduction', '自我介绍') !!}
		                {!! Form::textarea('introduction', $user->introduction, ['class' => 'form-control', 'required' => 'required']) !!}
		                <small class="text-danger">{{ $errors->first('introduction') }}</small>
		            </div>
		        
		            <div class="btn-group pull-right">
		                {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
		                {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
		            </div>
		        
		        {!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@stop


@extends('layouts.app')

@section('title')
    创建赛对局
@stop

@section('content')
     <div class="container">
     	<div class="panel panel-default">
     		<div class="panel-heading">
     			<h3 class="panel-title">创建对局信息</h3>
     		</div>
     		<div class="panel-body">
     			<div class="col-md-12">
     				{!! Form::open(['method' => 'POST', 'route' => 'match.store', 'class' => 'form-horizontal']) !!}
     				
     				    <div class="form-group{{ $errors->has('round') ? ' has-error' : '' }}">
     				        {!! Form::label('round', '比赛轮数(只允许填写数字)') !!}
     				        {!! Form::text('round', null, ['class' => 'form-control', 'required' => 'required']) !!}
     				        <small class="text-danger">{{ $errors->first('round') }}</small>
     				    </div>

     				    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
     				        {!! Form::label('type', '比赛类型(小组赛 or 淘汰赛)') !!}
     				        {!! Form::text('type', null, ['class' => 'form-control', 'required' => 'required']) !!}
     				        <small class="text-danger">{{ $errors->first('type') }}</small>
     				    </div>

     				    <div class="form-group{{ $errors->has('compare_id') ? ' has-error' : '' }}">
     				        {!! Form::label('compare_id', '该队伍属于哪个赛季') !!}
     				        {!! Form::select('compare_id', $compares, null, ['id' => 'compare_id', 'class' => 'form-control', 'required' => 'required']) !!}
     				        <small class="text-danger">{{ $errors->first('compare_id') }}</small>
     				    </div>

     				    <div class="form-group{{ $errors->has('TA') ? ' has-error' : '' }}">
     				        {!! Form::label('TA', '参赛队伍1') !!}
     				        {!! Form::text('TA', null, ['class' => 'form-control', 'required' => 'required']) !!}
     				        <small class="text-danger">{{ $errors->first('TA') }}</small>
     				    </div>

     				    <div class="form-group{{ $errors->has('TB') ? ' has-error' : '' }}">
     				        {!! Form::label('TB', '参赛队伍2') !!}
     				        {!! Form::text('TB', null, ['class' => 'form-control', 'required' => 'required']) !!}
     				        <small class="text-danger">{{ $errors->first('TB') }}</small>
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
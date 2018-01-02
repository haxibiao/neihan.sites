@extends('layouts.app')

@section('title')
    统计比赛结果
@stop

@section('content')
     <div class="container">
     	<div class="panel panel-default">
     		<div class="panel-heading">
     			<h3 class="panel-title">统计比赛结果</h3>
     		</div>
     		<div class="panel-body">
     			<div class="col-md-12">
     				{!! Form::open(['method' => 'PUT', 'route' => ['match.update',$match->id], 'class' => 'form-horizontal']) !!}
     				
     				    <div class="form-group{{ $errors->has('round') ? ' has-error' : '' }}">
     				        {!! Form::label('round', '比赛轮数(只允许填写数字)') !!}
     				        {!! Form::text('round', $match->id, ['class' => 'form-control', 'required' => 'required', 'disabled'=>'disabled']) !!}
     				        <small class="text-danger">{{ $errors->first('round') }}</small>
     				    </div>

     				    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
     				        {!! Form::label('type', '比赛类型(小组赛 or 淘汰赛)') !!}
     				        {!! Form::text('type', $match->type, ['class' => 'form-control', 'required' => 'required','disabled'=>'disabled']) !!}
     				        <small class="text-danger">{{ $errors->first('type') }}</small>
     				    </div>

     				    <div class="form-group{{ $errors->has('TA') ? ' has-error' : '' }}">
     				        {!! Form::label('TA', '参赛队伍1') !!}
     				        {!! Form::text('TA', $match->TA, ['class' => 'form-control', 'required' => 'required' ,'disabled'=>'disabled']) !!}
     				        <small class="text-danger">{{ $errors->first('TA') }}</small>
     				    </div>

     				    <div class="form-group{{ $errors->has('TB') ? ' has-error' : '' }}">
     				        {!! Form::label('TB', '参赛队伍2') !!}
     				        {!! Form::text('TB', $match->TB, ['class' => 'form-control', 'required' => 'required','disabled'=>'disabled']) !!}
     				        <small class="text-danger">{{ $errors->first('TB') }}</small>
     				    </div>

                             <div class="form-group{{ $errors->has('winner') ? ' has-error' : '' }}">
                                 {!! Form::label('winner', '获胜队伍') !!}
                                 {!! Form::text('winner', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                 <small class="text-danger">{{ $errors->first('winner') }}</small>
                             </div>

                             <div class="form-group{{ $errors->has('score') ? ' has-error' : '' }}">
                                 {!! Form::label('score', '比分统计') !!}
                                 {!! Form::text('score', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                 <small class="text-danger">{{ $errors->first('score') }}</small>
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
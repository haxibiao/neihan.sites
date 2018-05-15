@extends('layouts.app')

@section('title')
   创建新的队伍
@stop

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                创建新的队伍
            </h3>
        </div>
        <div class="panel-body">
            {!! Form::open(['method' => 'POST', 'route' => 'team.store', 'class' => 'form-horizontal']) !!}
            <div class="col-md-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::label('name', '名字') !!}
                   {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('name') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('compare_id') ? ' has-error' : '' }}">
                    {!! Form::label('compare_id', '该队伍的赛季') !!}
                   {!! Form::select('compare_id',$compares, null, ['id' => 'compare_id', 'class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('compare_id') }}
                    </small>
                </div>

                <div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
                    {!! Form::label('group', '该队伍的分组(只允许填写大写字母:如A)') !!}
                    {!! Form::text('group', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">{{ $errors->first('group') }}</small>
                </div>
                
                <div class="btn-group pull-right">
                    {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

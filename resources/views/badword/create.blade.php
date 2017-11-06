@extends('layouts.app')

@section('title')
    创建屏蔽词
@stop

@section('content')
<div class="container">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    创建屏蔽词
                </h3>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'POST', 'route' => 'badword.store', 'class' => 'form-horizontal']) !!}
                    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                        {!! Form::label('type', '屏蔽词类型') !!}
                        {!! Form::text('type', 'editor', ['class' => 'form-control', 'required' => 'required']) !!}
                        <small class="text-danger">
                            {{ $errors->first('type') }}
                        </small>
                    </div>
                    <div class="form-group{{ $errors->has('word') ? ' has-error' : '' }}">
                        {!! Form::label('word', '屏蔽词') !!}
                        {!! Form::text('word', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        <small class="text-danger">
                            {{ $errors->first('word') }}
                        </small>
                    </div>
                    <div class="btn-group pull-right">
                        {!! Form::submit("提交", ['class' => 'btn btn-success']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

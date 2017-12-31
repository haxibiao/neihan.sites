@extends('layouts.app')

@section('title')
    新建一个赛季
@stop

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                创建新的赛季
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                {!! Form::open(['method' => 'POST', 'route' => 'compare.store', 'class' => 'form-horizontal']) !!}
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::label('name', '赛季名称') !!}
                              {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('name') }}
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
@stop

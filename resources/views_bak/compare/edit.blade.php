@extends('layouts.app')

@section('title')
    编辑赛季
@stop

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
               编辑赛季
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                {!! Form::open(['method' => 'POST', 'route' => 'compare.store', 'class' => 'form-horizontal']) !!}
                
                 <div class="form-group{{ $errors->has('') ? ' has-error' : '' }}">
                     {!! Form::label('', 'Input label') !!}
                     {!! Form::text('', null, ['class' => 'form-control', 'required' => 'required']) !!}
                     <small class="text-danger">{{ $errors->first('') }}</small>
                 </div>
                   
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop
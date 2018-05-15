@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                文章推送
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-md-12">
                {!! Form::open(['method' => 'POST', 'route' => 'admin.push_article', 'class' => 'form-horizontal']) !!}
                <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                    {!! Form::label('number', '文章数目') !!}
				                                    {!! Form::text('number', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('number') }}
                    </small>
                </div>
                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                    {!! Form::label('type', '推送类型') !!}
				    {!! Form::select('type', [
	                  'baiduNumber'=>'百度推送',
				      'pandaNumber'=>'熊账号',], null, ['id' => 'type', 'class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('type') }}
                    </small>
                </div>
                <div class="btn-group ">
                    {!! Form::submit("提交", ['class' => 'btn btn-success']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

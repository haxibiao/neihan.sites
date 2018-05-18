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

                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    {!! Form::label('description', '赛季描述') !!}
                    {!! Form::text('description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">{{ $errors->first('description') }}</small>
                </div>

                <div class="form-group{{ $errors->has('count') ? ' has-error' : '' }}">
                    {!! Form::label('count', '参赛队伍数量') !!}
                        {!! Form::select('count', $options, null, ['id' => 'count', 'class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('count') }}
                    </small>
                </div>
                

                {!! Form::hidden('author', Auth::user()->name) !!}

                <div class="col-md-10" id='teams'>
                    
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

{{-- @push('scripts')
<script type="text/javascript">
    $('#count').change(function(){


         var temp = '<div class="form-group"><label for="team"></label><input class="form-control" placeholder="请输入" type="text" name="teams[]"></input></div>';
         var value=$(this).val();
         var total = '';
         for(var i=1;i<=value;i++){
            total += temp;
         }
         $("#teams").html(total);


      });
</script>
@endpush
 --}}
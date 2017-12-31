@extends('layouts.app')

@section('title')
     所有赛季
@stop

@section('content')
<div class="container">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    所有赛季
                </h3>
            </div>
            <div class="panel-body">
                这里show出所有的赛季
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <a aria-label="Left Align" class="btn btn-success btn-block" href="/compare/create">
                    <span aria-hidden="true" class="glyphicon glyphicon-pencil">
                    </span>
                    新建赛季
                </a>
            </div>
        </div>
    </div>
</div>
@stop

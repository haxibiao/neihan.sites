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
               <compare-form></compare-form>
        </div>
    </div>
</div>
@stop

@extends('v1.layouts.blank')

@section('title')
    关注 - 爱你城
@stop
@section('content')
<div id="subscriptions">
    <div class="container">
        <div class="row">
            <div class="aside col-xs-12 col-sm-3">
                <subscriptions-left></subscriptions-left>
            </div>
            <div class="main col-xs-12 col-sm-9">
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>
@stop

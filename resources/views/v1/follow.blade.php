@extends('v1.layouts.app')

@section('title')
    关注 - 爱你城
@stop
@section('content')
<div id="subscriptions">
    <div class="container">
        <div class="subscriptions_wrp clearfix">
            <div class="aside">
                <subscriptions-left></subscriptions-left>
            </div>
            <div class="main">
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>
@stop

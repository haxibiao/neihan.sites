@extends('v2.layouts.app')

@section('title')
    消息 - 爱你城
@stop
@section('content')
<div id="notifications">
    <div class="container">
        <div class="notifications_wrp clearfix">
            <div class="aside">
                <notifications-left>
                </notifications-left>
            </div>
            <div class="main">
                <router-view>
                </router-view>
            </div>
        </div>
    </div>
</div>
@stop

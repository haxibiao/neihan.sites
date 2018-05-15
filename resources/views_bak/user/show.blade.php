@extends('layouts.app')

@section('title')
    爱你城
@stop
@section('content')
<div id="user">
    <div class="container">
        <div class="row">
            <div class="main col-xs-12 col-sm-8">
                @include('user.parts._user_show_main_top')
                @include('user.parts._user_show_nav_tabs')
            </div>
            <div class="aside col-sm-4">
                @include('user.parts._user_show_aside')
        </div>
    </div>
</div>
@stop

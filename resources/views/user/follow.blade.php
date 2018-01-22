@extends('layouts.app')

@section('title')
    空评 - 爱你城
@stop
@section('content')
<div id="user">
    <div class="container">
        <div class="row">
            <div class="main col-xs-12 col-sm-8">
                @include('user.parts._user_show_main_top')
                @include('user.parts.menu_following')
            </div>
            @include('v2.parts.aside.aside_user')
        </div>
    </div>
</div>
@stop
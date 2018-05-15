@extends('v2.layouts.app')

@section('title')
    空评 - 爱你城
@stop
@section('content')
<div id="user">
    <div class="container">
        <div class="row">
            <div class="main col-xs-12 col-sm-8">
                @include('v2.parts.main_top.main_top_user')
                @include('v2.parts.menu.menu_liked_note_user')
            </div>
            @include('v2.parts.aside.aside_user')
        </div>
    </div>
</div>
@stop

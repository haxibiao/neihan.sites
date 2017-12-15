@extends('v1.layouts.app')

@section('title')
    喵星菇凉 - 爱你城
@stop
@section('content')
<div id="home">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                @include('v1.parts.main_top_home')
                @include('v1.parts.menu_following_home')
            </div>
            @include('v1.parts.aside_home')
        </div>
    </div>
</div>
@stop

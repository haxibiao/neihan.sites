@extends('v2.layouts.app')

@section('title')
    爱你城
@stop
@section('content')
<div id="home">
    <div class="container">
        <div class="row">
            <div class="main col-xs-12 col-sm-8">
                @include('v2.parts.main_top.main_top_home')
                @include('v2.parts.menu.menu_article_list')
            </div>
            @include('v2.parts.aside.aside_home')
        </div>
    </div>
</div>
@stop

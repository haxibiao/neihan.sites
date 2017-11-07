@extends('v1.layouts.blank')

@section('title')
    爱你城 - 最暖心的游戏社交网站
@stop
@section('content')
<div id="v1">
    <header class="heads">
        @include('v1.parts.head')
    </header>
    <div class="bodies">
        @include('v1.parts.index_middle')
    </div>
    <footer class="tail">
        @include('v1.parts.foot')
    </footer>
</div>
@stop

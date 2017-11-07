@extends('v1.layouts.blank')

@section('title')
    为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？ - 爱你城
@stop
@section('content')
<div id="v1">
    <header class="heads">
        @include('v1.parts.head')
    </header>
    <div class="between">
        @include('v1.parts.detail_middle')
    </div>
    <footer class="tail">
        @include('v1.parts.foot')
    </footer>
</div>
@stop

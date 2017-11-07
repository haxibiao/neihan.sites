@extends('layouts.blank')

@section('title')
    谈谈情，说说爱 - 专题 - 爱你城
@stop
@section('content')
<div id="v1">
    <header class="heads">
        @include('v1.parts.head')
    </header>
    <div class="centre">
        @include('v1.parts.among')
    </div>
    <footer class="tail">
        @include('v1.parts.foot')
    </footer>
</div>
@stop

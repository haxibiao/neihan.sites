@extends('layouts.app')

@section('title')
    喵星菇凉 - 爱你城
@stop
@section('content')
<div id="home">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                @include('user.parts.main_top_home')
                @include('user.parts.menu_liked_note_home')
            </div>
                @include('user.parts.aside_home')
        </div>
    </div>
</div>
@stop

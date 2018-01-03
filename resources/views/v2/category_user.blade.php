@extends('v2.layouts.app')

@section('title')
    谈谈情，说说爱 - 专题 - 爱你城
@stop
@section('content')
<div id="category">
    <div class="container">
        <div class="row">
            <div class="main col-xs-12 col-sm-8">
                @include('v2.parts.main_top.main_top_category_user')
                @include('v2.parts.menu.menu_category')
            </div>
            @include('v2.parts.aside.aside_category_user')
        </div>
    </div>
</div>
{{-- modal --}}
<categorymodal-user>
</categorymodal-user>
@stop

@extends('v2.layouts.app')

@section('title')
    好中文的样子 - 专题 - 爱你城
@stop
@section('content')
<div id="category">
    <div class="container">
        <div class="row">
            <div class="main col-xs-12 col-sm-8">
                @include('v2.parts.main_top.main_top_category_home')
                @include('v2.parts.menu.menu_category')
            </div>
            @include('v2.parts.aside.aside_category_home')
        </div>
    </div>
</div>
{{-- modal --}}
{{-- <categorymodal-home>
</categorymodal-home> --}}
@stop

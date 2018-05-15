@extends('v1.layouts.app')

@section('title')
    好中文的样子 - 专题 - 爱你城
@stop
@section('content')
<div id="category">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                @include('v1.parts.main_top_category_home')
                @include('v1.parts.menu_category')
            </div>
            @include('v1.parts.aside_category_home')
        </div>
    </div>
</div>
{{-- modal --}}
<categorymodal-home>
</categorymodal-home>
@stop

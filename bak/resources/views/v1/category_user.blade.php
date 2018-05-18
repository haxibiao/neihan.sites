@extends('v1.layouts.app')

@section('title')
    谈谈情，说说爱 - 专题 - 爱你城
@stop
@section('content')
<div id="category">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                @include('v1.parts.main_top_category_user')
                @include('v1.parts.menu_category')
            </div>
            @include('v1.parts.aside_category_user')
        </div>
    </div>
</div>
{{-- modal --}}
<categorymodal-user>
</categorymodal-user>
@stop

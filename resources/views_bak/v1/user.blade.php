@extends('v1.layouts.app')

@section('title')
    空评 - 爱你城
@stop
@section('content')
<div id="user">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                @include('v1.parts.main_top_user')
                @include('v1.parts.menu_article_list_user')
            </div>
            @include('v1.parts.aside_user')
        </div>
    </div>
</div>
@stop

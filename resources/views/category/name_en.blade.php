@extends('layouts.app')

@section('title')
    谈谈情，说说爱 - 专题 - 爱你城
@stop
@section('content')
<div id="category">
    <div class="container">
        <div class="row">
            <div class="main col-xs-12 col-sm-8">
                @include('category.parts.category_user',['category'=>$category])
                @include('category.parts.category_navtable',['category'=>$category])
            </div> 
           {{-- 专题右侧 --}}
                @include('category.parts.category_right',['category'=>$category])
        </div>
    </div>
</div>
@stop

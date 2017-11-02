@extends('layouts.app')

@section('title')
     图片列表
@stop

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li>
            <a href="/">
                {{ config('app.name') }}
            </a>
        </li>
        <li class="active">
            图片
        </li>
    </ol>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title" style="line-height: 30px">
                图片列表
            </h3>
        </div>
        <div class="panel-body">
            @foreach($images as $image)
                @include('image.parts.image_item')
            @endforeach
            <p>
                {{ $images->render() }}
            </p>
        </div>
    </div>
</div>
@stop

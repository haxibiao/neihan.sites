@extends('layouts.blank')
@section('title')
    注册 - {{ env('APP_NAME') }}
@stop　
@section('content')
<div id="login">
    <div class="logo"><a href="/"><img src="{{'/logo/'.get_domain().'.text.png' ?:'/logo/'.get_domain().'.png' }}" alt="{{ config('app.name') }}"></a></div>
    <signs register></signs>
   
    @if($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>出错了！</strong> 
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@stop
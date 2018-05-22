@extends('layouts.blank')

@section('content')

@php
    //登录成功返回之前的页面
    session()->put('url.intended', request()->headers->get('referer'));
@endphp

<div id="login">
    <div class="logo"><a href="/"><img src="{{'/logo/'.get_domain().'.text.png' ?:'/logo/'.get_domain().'.png' }}" alt="{{ config('app.name') }}"></a></div>
    <signs></signs>
    
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
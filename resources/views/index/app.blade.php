@extends('layouts.app')

@section('title')
	{{ config('app.name') }}客户端/App/应用下载 
@stop

@section('content')
<div class="container">
   <div class="panel panel-info">
   	<div class="panel-heading">
   		<h3 class="panel-title">关于{{ config('app.name') }}客户端</h3>
   	</div>
   	<div class="panel-body">
   		<h1>
            {{ config('app.name') }}客户端/App/应用的下载
        </h1>
        <p>
            我们目前还在紧张的开发和测试APP, 请过几天来关注这里，随时会发布哦 ...
        </p>
        <p>
            <a class="btn btn-danger btn-lg" href="http://haxibiao-1251052432.cosgz.myqcloud.com/dongdianyi.apk" target="_blank">
                下载{{ config('app.name') }}安卓内部测试版APP
            </a>
        <hr>
            <span class="alert alert-info">
                微信用户注意点右上角的 ...
            </span>
        </p>
   	</div>
   </div>
</div>
@stop

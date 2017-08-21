@extends('layouts.app')

@section('title')
	{{ config('app.name') }}客户端/App/应用下载 
@stop

@section('content')
<div class="container">
   <div class="panel panel-info">
   	<div class="panel-heading">
   		<h3 class="panel-title">{{ config('app.name') }}App</h3>
   	</div>
   	<div class="panel-body">
   		<h2>
            关于{{ config('app.name') }}App
        </h2>
        <p>
            我们目前还在紧张的开发和测试APP, 请过几天来关注这里，随时会发布正式版 ...
        </p>
        <p>
            <a class="btn btn-success" href="https://haxibiao.com/{{ get_domain() }}.apk" target="_blank">
                下载安卓版(内测App)
            </a>
        <hr>
            <p class="small">
              微信用户注意点右上角的 ...　然后选择浏览器打开，就可以下载成功.
            </p>
        </p>
   	</div>
   </div>
</div>
@stop

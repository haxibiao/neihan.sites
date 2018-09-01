@extends('layouts.app')
@section('title')
    我的设置 - {{ env('APP_NAME') }}
@stop
@section('content')
	<div id="setting">
		<section class="left-aside clearfix">
			<setting-aside></setting-aside>
			<div class="main">
				<router-view></router-view>
			</div>
		</section>
	</div>
@endsection 
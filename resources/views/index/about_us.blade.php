@extends('layouts.app')

@section('title')
	关于我们
@stop

@section('content')
	<div class="container">
		<p>
			{{ config('app.name', 'Laravel') }} 专注 @foreach(get_categories() as $cate_id => $cate_name) {{ $cate_name }},  @endforeach 方面的原创内容,专注分享,方便中国移动互联网网民学习交流.
		</p>
	</div>
@stop
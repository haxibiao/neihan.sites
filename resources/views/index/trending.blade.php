{{-- 新上榜，七日，30日热门。文章和banner不同--}}
@extends('layouts.app')
@section('title')
	@if(request('type')=="seven")
		7日热门
	@elseif(request('type')=="thirty")
	   30日热门
	@else
	   经典热门
	@endif
	 - {{ env('APP_NAME') }}
@stop 
@section('content')
	<div id="trending">
		<div class="clearfix">
			<div class="main sm-left">
				{{-- <img src="/images/{{ request('type') }}-hot.png" alt="" class="page-banner"> --}}
				@if(request('type')=="seven")
					{{-- 七日热门 --}}
					@include('parts.trending_seven_list')
				@elseif (request('type')=="thirty")
					{{-- 30日热门 --}}
					@include('parts.trending_thirty_list')
				@else
					{{-- 新上榜 --}}
					@include('parts.trending_new_list')
				@endif
				<ul class="article-list">
					@each('parts.article_item', $articles, 'article')
				</ul>
			</div>
			{{-- 侧栏 --}}
			<div class="aside sm-right hidden-xs">
				@if(Auth::check())
				<recommend-authors></recommend-authors>
				@endif
			</div>
		</div>
	</div>
@endsection
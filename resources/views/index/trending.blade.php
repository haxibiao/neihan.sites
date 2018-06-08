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
				{{-- 新上榜 --}}
				 @if(request('type')=="new")
				  	@include('parts.trending_new_list')
				 @endif
			
				{{-- 七日热门 --}}
				@if(request('type')=="seven")
				@include('parts.trending_seven_list')
				@endif
				{{-- 30日热门 --}}
				@if(request('type')=="thirty")
				@include('parts.trending_thirty_list')
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
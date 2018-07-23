@extends('layouts.app')

@section('title')专题首页 - {{ env("APP_NAME") }} @endsection

@section('content')
<div id="categories">
	<div class="page-banner">
		{{-- <img src="/images/recommend_banner.png" alt=""> --}}
		<div class="banner-img recommend-banner">
			<div>
				<i class="iconfont icon-zhuanti1"></i>
				<span>热门专题</span>
			</div>
		</div>
		<a target="_blank" class="help" href="/article/{{ config('editor.category.how_to_create') }}">
		    <i class="iconfont icon-bangzhu"></i>
		    如何创建并玩转专题
		</a>
	</div>
	  <!-- Nav tabs -->
	  <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
	   <li role="presentation" class="active">
	   	<a href="#recommend" aria-controls="recommend" role="tab" data-toggle="tab"><i class="iconfont icon-tuijian1"></i>推荐</a>
	   </li>
	   <li role="presentation">
	   	<a href="#hot" aria-controls="hot" role="tab" data-toggle="tab"><i class="iconfont icon-huo"></i>热门</a>
	   </li>
	 {{--   <li role="presentation">
	   	<a href="#city" aria-controls="city" role="tab" data-toggle="tab"><i class="iconfont icon-icon2"></i>城市</a>
	   </li> --}}
	  </ul>
		<!-- Tab panes -->
		<div class="recommend-list tab-content">
	   <ul role="tabpanel" class="fade in tab-pane active clearfix" id="recommend">
			@each('category.parts.category_card', $data['recommend'], 'category')
			@if(Auth::check())
			<category-list api="{{ request()->path() }}?recommend=1" start-page="2"></category-list>
			@else
			<div>{!! $data['recommend']->links() !!}</div>
			@endif
	   </ul>
	   <ul role="tabpanel" class="fade tab-pane clearfix" id="hot">
			@each('category.parts.category_card', $data['hot'], 'category')
			@if(Auth::check())
			<category-list api="{{ request()->path() }}?hot=1" start-page="2"></category-list>
			@else
			<div>{!! $data['hot']->links() !!}</div>
			@endif
	   </ul>
	   {{-- <ul role="tabpanel" class="fade tab-pane clearfix" id="city">
			@each('category.parts.category_card', $data['city'], 'category')
			@if(Auth::check())
			<category-list api="{{ request()->path() }}?city=1" start-page="2"></category-list>
			@else
			<div>{!! $data['city']->links() !!}</div>
			@endif
	   </ul> --}}
	</div>
</div>
@endsection
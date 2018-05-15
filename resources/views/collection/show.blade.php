@extends('layouts.app')

@section('title') {{ $collection->user->name }}的{{ $collection->name }} - @endsection
@section('keywords') {{ $collection->user->name }}的{{ $collection->name }} @endsection
@section('description') {{ $collection->user->name }}的{{ $collection->name }} @endsection

@section('content')
	<div id="collection">
		<section class="clearfix">
			<div class="main col-sm-7">
				{{-- 信息 --}}
				@include('collection.parts.information')
				{{-- 内容 --}}
				<div class="content">
					<!-- Nav tabs -->
					 <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
					   <li role="presentation" class="active">
					   	<a href="#article" aria-controls="article" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>最新发表</a>
					   </li>
					   <li role="presentation">
					   	<a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>最新评论</a>
					   </li>
					   <li role="presentation">
					   	<a href="#catalog" aria-controls="catalog" role="tab" data-toggle="tab"><i class="iconfont icon-duoxuan"></i>目录</a>
					   </li>
					 </ul>
					 <!-- Tab panes -->
					 <div class="article_list tab-content">
					   <ul role="tabpanel" class="fade in note_list tab-pane active" id="article">
   		 					@each('parts.article_item', $data['new'], 'article')
					   </ul>
					   <ul role="tabpanel" class="fade note_list tab-pane" id="comment">
	   						@each('parts.article_item', $data['commented'], 'article')
					   </ul>
					   <ul role="tabpanel" class="fade note_list tab-pane" id="catalog">
					   		@each('parts.article_item', $data['old'], 'article')
					   </ul>
					 </div>
				</div>
			</div>
			<div class="aside col-sm-4 col-sm-offset-1">
				<div class="share distance">
					<span>分享到</span>
					<a href="javascript:;"><i class="weibo iconfont icon-weixin1"></i></a>
					<a href="javascript:;"><i class="weixin iconfont icon-sina"></i></a>
					<a href="javascript:;"><i class="more iconfont icon-gengduo"></i></a>
				</div>
				<div class="administrator">
					<p class="plate-title">文集作者</p>
					<ul>
						<li><a href="/user/{{ $collection->user->id }}" class="avatar"><img src="{{ $collection->user->avatar() }}" alt=""></a><a href="/user/{{ $collection->user->id }}" class="name">{{ $collection->user->name }}</a></li>
					</ul>
				</div>
			</div>
		</section>
	</div>
@endsection
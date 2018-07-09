@extends('layouts.app')
	
@section('title') 搜索 - {{ env('APP_NAME') }}  @endsection

@section('content')
	<div id="search-content" class="articles">
		<section class="left-aside clearfix">
			@include('search.aside') 
			<div class="main">
				<div class="search-content">
					<div class="plate-title">
						<span>综合排序</span>
						<a href="javascript:;" class="right">{{ $data['video']->total() }} 个结果</a>
					</div>
					<div class="note-list">
						@foreach($data['video'] as $article) 
						<li class="article-item {{ $article->hasImage() ? 'have-img' : '' }}">
					      @if($article->hasImage())
							  <a class="wrap-img" href="{{ $article->content_url() }}" target="_blank">
							      <img src="{{ $article->primaryImage() }}" alt="">
							  </a>
						  @endif 
						  <div class="content">
						    <div class="author">
						      <a class="avatar" target="_blank" href="/user/{{ $article->user_id }}">
						        <img src="{{ $article->user->avatar() }}" alt="">
						      </a> 
						      <div class="info">
						        <a class="nickname" target="_blank" href="/user/{{ $article->user_id }}">{{ $article->user->name }}</a>
						        <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
						        <span class="time" data-shared-at="{{ $article->created_at }}">{{ $article->timeAgo() }}</span>
						      </div> 
						    </div>
						    <a class="title" target="_blank" href="{{ $article->content_url() }}">
						        <span>{!! $article->title !!}</span>
						    </a>
						    <p class="abstract"> 
						      {!! $article->description !!}
						    </p>
						    <div class="meta">
						      <a target="_blank" href="{{ $article->content_url() }}">
						        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
						      </a>        
						      <a target="_blank" href="{{ $article->content_url() }}">
						        <i class="iconfont icon-svg37"></i> {{ $article->count_replies }}
						      </a>      
						      <span><i class="iconfont icon-03xihuan"></i> {{ $article->count_likes }}</span>
						      @if($article->count_tips)
						      <span><i class="iconfont icon-qianqianqian"></i> {{ $article->count_tips }}</span>
						      @endif
						    </div>
						  </div>
						</li>
						@endforeach
					</div>
					@if(!$data['video']->total())
						<blank-content></blank-content>
					@endif
					{!! $data['video']->appends(['q'=>$data['query']])->render() !!}
				</div>
			</div>
		</section>
	</div>
@endsection 
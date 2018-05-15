@extends('layouts.app')
	
@section('content')
	<div id="search-content" class="articles">
		<section class="left-aside clearfix">
			@include('search.aside')
			<div class="main">
				<div class="top">
					<div class="relevant">
						<div class="plate-title">
							<span>相关用户</span>
							<a href="/search/users{{ request('q') ? '?q='.request('q') : '' }}" class="all right">查看全部<i class="iconfont icon-youbian"></i></a>
						</div>
						<div class="container-fluid list">
							<div class="row">
								@foreach($data['users'] as $user) 
								<div class="col-sm-4 col-xs-12">
									<div class="user-info user-sm">
										<div class="avatar">
											<img src="{{ $user->avatar() }}" alt="">
										</div>
										<div class="title">
											<a href="/user/{{ $user->id }}" class="name">{{ $user->name }}</a>
										</div>
										<div class="info">写了{{ $user->count_words }}字 · {{ $user->count_likes }}喜欢</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
					<div class="relevant">
						<div class="plate-title">
							<span>相关专题</span>
							<a href="/search/categories{{ request('q') ? '?q='.request('q') : '' }}" class="all right">查看全部<i class="iconfont icon-youbian"></i></a>
						</div>
						<div class="container-fluid list">
							<div class="row">
								@foreach($data['categories'] as $category) 
								<div class="col-sm-4 col-xs-12">
									<div class="note-info note-sm">
										<div class="avatar-category">
											<img src="{{ $category->logo() }}" alt="">
										</div>
										<div class="title">
											<a href="/{{ $category->name_en }}" class="name">{{ $category->name }}</a>
										</div>
										<div class="info">收录了{{ $category->count }}篇文章 · {{ $category->count_follows }}人关注</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<div class="search-content">
					<div class="plate-title">
						<span>综合排序</span>
						<a href="javascript:;" class="right">{{ $data['total'] }} 个结果</a>
					</div>
					<div class="note-list">
						@foreach($data['articles'] as $article) 
						<li class="article-item">
						  <a class="wrap-img" href="/article/{{ $article->id }}" target="_blank">
						      <img src="{{ $article->primaryImage() }}" alt="">
						  </a>
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
						    <a class="title" target="_blank" href="/article/{{ $article->id }}">
						        <span>{!! $article->title !!}</span>
						    </a>
						    <p class="abstract">
						      {!! $article->description !!}
						    </p>
						    <div class="meta">
						      <a target="_blank" href="/article/{{ $article->id }}">
						        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
						      </a>        
						      <a target="_blank" href="/article/{{ $article->id }}">
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

					@if(!$data['articles']->total())
						<blank-content></blank-content>
					@endif

					{!! $data['articles']->appends(['q'=>$data['query']])->render() !!}
				</div>
			</div>
		</section>
	</div>
@endsection
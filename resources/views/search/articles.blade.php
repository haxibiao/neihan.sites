@extends('layouts.app')

@section('title') 搜索 - {{ config("app.name_cn") }}  @endsection

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
											<img src="{{ $user->avatarUrl }}" alt="">
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
											<img src="{{ $category->logoUrl }}" alt="">
										</div>
										<div class="title">
											<a href="/category/{{ $category->id }}" class="name">{{ $category->name }}</a>
										</div>
										<div class="info">收录了{{ $category->publishedWorks()->count() }}篇作品 · {{ $category->count_follows }}人关注</div>
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
						<li class="article-item {{ $article->cover ? 'have-img' : '' }}">
					      @if($article->cover)
							  <a class="wrap-img" href="{{ $article->url }}" target="_blank">
							      <img src="{{ $article->cover }}" alt="">
							  </a>
						  @endif
						  <div class="content">
						    <div class="author">
						      <a class="avatar" target="_blank" href="/user/{{ $article->user_id }}">
						        <img src="{{ $article->user->avatarUrl }}" alt="">
						      </a>
						      <div class="info">
						        <a class="nickname" target="_blank" href="/user/{{ $article->user_id }}">{{ $article->user->name }}</a>

						        @if($article->user->is_signed)
						        <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
						        @endif
						        @if($article->user->is_editor)
						          <img class="badge-icon" src="/images/editor.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}小编" alt="">
						        @endif

						        <span class="time" data-shared-at="{{ $article->created_at }}">{{ $article->timeAgo() }}</span>
						      </div>
						    </div>
						    <a class="title" target="_blank" href="{{ $article->url }}">
						        <span>{!! $article->subject !!}</span>
						    </a>
						    <p class="abstract">
						      {!! $article->description !!}
						    </p>
						    <div class="meta">
						      <a target="_blank" href="{{ $article->url }}">
						        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
						      </a>
						      <a target="_blank" href="{{ $article->url }}">
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
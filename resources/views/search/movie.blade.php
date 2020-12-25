@extends('layouts.app')

@section('content')
	<div id="search-content">
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
						<a href="javascript:;" class="right">{{ $data['movie']->total() }} 个结果</a>
					</div>
					<div class="note-list">
						@foreach($data['movie'] as $movie)
						<li class="note-info"><a href="/movie/{{ $movie->id }}" class="avatar-category">
							<img src="{{ $movie->cover }}" alt=""></a>
							<div class="title"><a href="/movie/{{ $movie->id }}" class="name">{{ $movie->name }}</a></div>
							<div class="info">
                                <p>主演:
                                    @if($movie->actors)
                                        {{$movie->actors}}
                                    @else
                                        未知
                                    @endif
                                </p>
                            </div></li>
						@endforeach
					</div>
					@if(!$data['movie']->total())
						<blank-content></blank-content>
                    @endif
                    {!! $data['movie']->links() !!}
				</div>
			</div>
		</section>
	</div>
@endsection
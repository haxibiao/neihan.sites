@extends('layouts.app')

@section('title') 搜索 - {{ env('APP_NAME') }}  @endsection

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
						<div class="row videos distance">
	             <div class="col-xs-6 col-md-4 video">
	               <div class="video-item vt">
	                 <div class="thumb">
	                   <a href="#">
	                     <img src="https://ainicheng.com/storage/video/294.jpg" alt="绝地求生：呆妹儿：你有女朋友吗？小学生：你给我闭嘴">
	                     <i class="duration">
	                       {{-- 持续时间 --}}
	                       04:18
	                     </i>
	                   </a>
	                 </div>
	                 <ul class="info-list">
	                   <li class="video-title">
	                     <a href="#">绝地求生：呆妹儿：你有女朋友吗？小学生：你给我闭嘴</a>
	                   </li>
	                   <li>
	                     {{-- 播放量 --}}
	                     <p class="subtitle single-line">21次播放</p>
	                   </li>
	                 </ul>
	               </div>
	             </div>
	          </div>
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
@extends('layouts.app')
	
@section('content')
	<div id="search-content">
		<section class="left-aside clearfix">
			@include('search.aside')
			<div class="main">
				<div class="search-content">
					<div class="plate-title">
						<span>综合排序</span>
						<a href="javascript:;" class="right">{{ $data['users']->total() }} 个结果</a>
					</div>
					<div class="user-list follow-user-list">
						@foreach($data['users'] as $user) 
						<li class="user-info info-md">
						    <a class="avatar" href="/user/{{ $user->id }}">
						      <img src="{{ $user->avatar() }}" alt="">
						      </a>        
						      <follow
								type="users"
								id="{{ $user->id }}" 
								user-id="{{ user_id() }}" 
      							followed="{{ is_follow('users', $user->id) }}">
						      </follow>
						    <div class="title">
						      <a href="/user/{{ $user->id }}" class="name">{{ $user->name }}</a>
						    </div>
						    <div class="info">
						      <div class="meta hidden-xs">
						          <span>关注 {{ $user->count_followings }}</span><span>粉丝 {{ $user->count_follows }}</span><span>文章 {{ $user->count_articles }}</span>
						        </div>
						        <div class="meta">
						          写了 {{ $user->count_words }} 字，获得了 {{ $user->count_likes }} 个喜欢
						        </div>
						    </div>
						</li>
						@endforeach
					</div>
					@if(!$data['users']->total())
						<blank-content></blank-content>
					@endif
					{!! $data['users']->links() !!}
				</div>
			</div>
		</section>
	</div>
@endsection
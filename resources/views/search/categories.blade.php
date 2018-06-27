@extends('layouts.app')
	
@section('content')
	<div id="search-content">
		<section class="left-aside clearfix">
			@include('search.aside')
			<div class="main">
				<div class="search-content">
					<div class="plate-title">
						<span>综合排序</span>
						<a href="javascript:;" class="right">{{ $data['categories']->total() }} 个结果</a>
					</div>
					<div class="note-list">
						@foreach($data['categories'] as $category) 
						<li class="note-info"><a href="/{{ $category->name_en }}" class="avatar-category">
							<img src="{{ $category->logo() }}" alt=""></a>
							<follow 
					            type="categories" 
					            id="{{$category->id}}" 
					            user-id="{{ user_id() }}" 
					            followed="{{ is_follow('categories', $category->id) }}">  
					          </follow>
							<div class="title"><a href="/{{ $category->name_en }}" class="name">{{ $category->name }}</a></div>
							<div class="info"><p>收录了{{ $category->count }}篇文章，{{ $category->count_follows }}人关注</p></div></li>
						@endforeach
					</div>
					@if(!$data['categories']->total())
						<blank-content></blank-content>
					@endif
					{!! $data['categories']->links() !!}
				</div>
			</div>
		</section>
	</div>
@endsection
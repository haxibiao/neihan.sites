@extends('layouts.app')
	
@section('content')
	<div id="search-content">
		<section class="left-aside clearfix">
			@include('search.aside')
			<div class="main">
				<div class="search-content">
					<div class="plate-title">
						<span>综合排序</span>
						<a href="javascript:;" class="right">{{ $data['collections']->total() }} 个结果</a>
					</div>
					<div class="note-list">
						@foreach($data['collections'] as $collection) 
						<li class="note-info"><a href="/collection/{{ $collection->id }}" class="avatar-category">
							<img src="{{ $collection->logo() }}" alt=""></a>
							<follow></follow>
							<div class="title"><a href="/collection/{{ $collection->id }}" class="name">{{ $collection->name }}</a></div>
							<div class="info"><p>{{ $collection->count }}篇文章，{{ $collection->count_follows }}人关注</p></div></li>
						@endforeach
					</div>
					@if(!$data['collections']->total())
						<blank-content></blank-content>
					@endif
					{!! $data['collections']->links() !!}
				</div>
			</div>
		</section>
	</div>
@endsection
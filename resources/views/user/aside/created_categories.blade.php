<div class="administrator distance">	
	<p class="plate-title">
		{{ $user->ta() }}创建的专题
		@if($user->isSelf())
		<a class="right new-note-btn" href="/category/create"><i class="iconfont icon-jia1"></i>新建专题</a>
		@endif
	</p>	
	<ul>
		@foreach($user->hasManyCategories()->orderBy('id','desc')->take(5)->get() as $category)
		<li class="single-media"><a href="/{{ $category->name_en }}" class="avatar-category"><img src="{{ $category->logo() }}" alt="{{ $category->name }}"></a><a href="/{{ $category->name_en }}" class="info">{{ $category->name }}</a></li>	
		@endforeach
	</ul>
</div>
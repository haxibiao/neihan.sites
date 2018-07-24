<div class="administrator distance">
	<p class="plate-title">{{ $user->ta() }}管理的专题</p>
	<ul>
		@foreach($user->adminCategories()->orderBy('id','desc')->get() as $category)
		<li class="single-media"><a href="/{{ $category->name_en }}" class="avatar-category"><img src="{{ $category->logo() }}" alt="{{ $category->name }}"></a><a href="/{{ $category->name_en }}" class="info">{{ $category->name }}</a></li>	
		@endforeach
	</ul>
</div>
<div class="administrator distance">	
	<p class="plate-title">
		{{ $user->ta() }}创建的专题
		@if($user->isSelf())
		
		@endif
	</p>	
	<ul>
		@foreach($user->hasManyCategories()->orderBy('id','desc')->take(5)->get() as $category)
		<li class="single-media"><a href="/category/{{ $category->id }}" class="avatar-category"><img src="{{ $category->logoUrl }}" alt="{{ $category->name }}"></a><a href="/category/{{ $category->id }}" class="info">{{ $category->name }}</a></li>	
		@endforeach
	</ul>
</div>
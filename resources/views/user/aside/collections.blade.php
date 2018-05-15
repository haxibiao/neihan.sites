<div class="collection distance">
    <p class="plate-title">他的文集</p>
    <ul class="icon-text-list">
    	@foreach($user->collections()->orderBy('id','desc')->take(5)->get() as $collection)
        <li><a href="/collection/{{ $collection->id }}"><i class="iconfont icon-wenji"></i> <span>{{ $collection->name }}</span></a></li>
        @endforeach
    </ul>
</div>
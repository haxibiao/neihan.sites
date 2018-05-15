<div class="recom-author distance">
	<p class="plate-title">推荐作者<i class="iconfont icon-tuijian1"></i></p>
    <ul class="list-people"> 
        
        @foreach($category->topAuthors() as $user)
        <li>
			<a class="avatar" href="/user/{{ $user->id }}" data-toggle="tooltip" data-placement="bottom" title="{{ $user->name }}">
				<img src="{{ $user->avatar() }}" alt="">
			</a>
		</li>
        @endforeach
        
        @if($category->authors()->count() > 8)
        <a class="more iconfont icon-gengduo"></a>
        @endif
    </ul>
</div>
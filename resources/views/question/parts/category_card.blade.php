<li class="col-sm-4 recommend-card">
  <div>
    <a target="_blank" href="/question?cid={{ $category->id }}">
      	<img class="avatar-category" src="{{ $category->logo() }}" alt="">
      <h4 class="name single-line">{{ $category->name }}</h4>
      <p class="category-description">
        {{ $category->description }}
			</p>
		</a>    
		<a class="btn-base btn-follow"><span><i class="iconfont icon-icon20"></i>关注</span></a>
    <hr>
    <div class="count"><a target="_blank" href="javascript:;">{{ $category->count_questions }}个问题</a> · {{ $category->count_follows }}人关注</div>
  </div>
</li>
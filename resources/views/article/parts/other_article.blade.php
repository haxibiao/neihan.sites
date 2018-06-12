{{-- 阅读更多  详细版 --}}
<div class="read-more">
	<h4 class="plate-title underline"><span>继续阅读</span></h4>
	<div class="article-item {{ $article->hasImage() ? 'have-img' : '' }}">
	  @if($article->hasImage())
	    <a class="wrap-img" href="/article/{{ $article->id }}" target="_blank">
	        <img src="{{ $article->primaryImage() }}" alt="{{$article->title}}">
	    </a>
	  @endif
	  <div class="content">
	    <a class="title" target="_blank" href="/article/{{ $article->id }}">
	        <span>{{ $article->title }}</span>
	    </a>
	    <p class="abstract">
	      {{ $article->description() }}
	    </p>
	    <div class="meta">
	      <a target="_blank" href="/article/{{ $article->id }}" class="browse_meta">
	        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
	      </a>
	      <a target="_blank" href="/article/{{ $article->id }}/#comments" class="comment_meta">
	        <i class="iconfont icon-svg37"></i> {{ $article->count_replies }}
	      </a>
	      <span><i class="iconfont icon-03xihuan"></i> {{ $article->count_likes }} </span>
	      @if($article->count_tips)
	        <a><i class="iconfont icon-qianqianqian"></i> {{ $article->count_tips }}</a>
	      @endif
	    </div>
	  </div>
	</div>
	<div class="article-item {{ $article->hasImage() ? 'have-img' : '' }}">
	  @if($article->hasImage())
	    <a class="wrap-img" href="/article/{{ $article->id }}" target="_blank">
	        <img src="{{ $article->primaryImage() }}" alt="{{$article->title}}">
	    </a>
	  @endif
	  <div class="content">
	    <a class="title" target="_blank" href="/article/{{ $article->id }}">
	        <span>{{ $article->title }}</span>
	    </a>
	    <p class="abstract">
	      {{ $article->description() }}
	    </p>
	    <div class="meta">
	      <a target="_blank" href="/article/{{ $article->id }}" class="browse_meta">
	        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
	      </a>
	      <a target="_blank" href="/article/{{ $article->id }}/#comments" class="comment_meta">
	        <i class="iconfont icon-svg37"></i> {{ $article->count_replies }}
	      </a>
	      <span><i class="iconfont icon-03xihuan"></i> {{ $article->count_likes }} </span>
	      @if($article->count_tips)
	        <a><i class="iconfont icon-qianqianqian"></i> {{ $article->count_tips }}</a>
	      @endif
	    </div>
	  </div>
	</div>
</div>
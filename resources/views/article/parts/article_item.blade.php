<div class="media">
	@if($article->cover)
	<a class="pull-left hidden-sm hidden-xs" href="{{ get_article_url($article) }}" {!! $article->target_url ? ' ' : '' !!}>
		<img class="media-object" src="{{ $article->cover }}" alt="{{ $article->subject }}" style="max-width: 160px">
	</a>
	<a class="visible-sm visible-xs" href="/article/{{ $article->id}}" {!! $article->target_url ? ' ' : '' !!}>
		<img class="media-object　img img-responsive" src="{{ $article->cover }}" alt="{{ $article->subject }}">
	</a>
	@endif
	<div class="media-body　strip_title">
		<a href="/article/{{ $article->id}}" {!! $article->target_url ? ' ' : '' !!}>
			<h5 class="media-heading">{{ $article->subject }}</h5>
		</a>
		<p class="small">发布时间:　{{ $article->timeAgo() }}</p>
		@if(!empty($search) && !empty($article->keywords))<p class="small">关键词:　{{ $article->keywords }}</p>@endif
		<p title="{{ $article->description }}">{{ str_limit($article->description, 80) }}</p>
	</div>
</div>
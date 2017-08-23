<div class="media">
	@if(!empty($article->image_url))
	<a class="pull-left hidden-sm hidden-xs" href="{{ get_article_url($article) }}" {!! $article->target_url ? 'target="_blank"' : '' !!}>
		<img class="media-object" src="{{ get_small_image($article->image_url) }}" alt="{{ $article->title }}" style="max-width: 160px">
	</a>
	<a class="visible-sm visible-xs" href="{!! app_link($article) !!}" {!! $article->target_url ? 'target="_blank"' : '' !!}>
		<img class="media-object" src="{{ get_small_image($article->image_url) }}" alt="{{ $article->title }}" style="max-width: 200px; padding-bottom: 10px">
	</a>
	@endif
	<div class="media-body　strip_title">
		<a href="{!! app_link($article) !!}" {!! $article->target_url ? 'target="_blank"' : '' !!}>
			<h5 class="media-heading">{{ $article->title }}</h5>
		</a>
		<p class="small">发布时间:　{{ diffForHumansCN($article->created_at) }}</p>
		@if(!empty($search))<p class="small">关键词:　{{ $article->keywords }}</p>@endif
		<p title="{{ $article->description }}">{{ str_limit($article->description, 80) }}</p>
	</div>
</div>
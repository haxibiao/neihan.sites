<div class="media">
	@if(!empty($article->image_url))
	<a class="pull-left" href="/article/{{ $article->id }}">
		<img class="media-object" src="{{ get_small_article_image($article->image_url) }}" alt="{{ $article->title }}" style="max-width: 160px">
	</a>
	<a class="visible-sm visible-xs" href="/article/{{ $article->id }}">
		<img class="media-object" src="{{ get_small_article_image($article->image_url) }}" alt="{{ $article->title }}" style="max-width: 200px; padding-bottom: 10px">
	</a>
	@endif
	<div class="media-body　strip_title">
		<a href="/article/{{ $article->id }}">
			<h5 class="media-heading">{{ $article->title }}</h5>
		</a>
		<p title="{{ $article->description }}">{{ str_limit($article->description, 80) }}</p>
	</div>
</div>
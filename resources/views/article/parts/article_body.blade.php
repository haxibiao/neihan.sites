{{-- 详情页的主文章 --}}
<div class="article_content">
     @if($article->music)
	         <audio src="{{ $article->music->path }}" controls="controls">
				Your browser does not support the audio tag.
			</audio>
     @endif

      {!! $article->body !!}
</div>
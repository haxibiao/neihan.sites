{{-- 详情页的主文章 --}}
<div class="article_content">
     @if(!empty($article->music))
        @foreach($article->music as $music)
	         <audio src="{{ $music->path }}" controls="controls">
				Your browser does not support the audio tag.
			</audio>
		@endforeach
     @endif

      {!! $article->body !!}
</div>
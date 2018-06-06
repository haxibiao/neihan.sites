@if(!empty($article->description))
<p class="lead">
  {{ $article->description() }}
</p>
@endif
<p>
	@php	
		$body = str_replace('div', 'p', $article->body);
		$body = str_replace('class="container"', '', $body);
	@endphp
	{!! $body !!}
</p>
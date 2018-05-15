@if(!empty($article->description))
<p class="lead">
  {{ $article->description() }}
</p>
@endif
{!! $article->body !!}
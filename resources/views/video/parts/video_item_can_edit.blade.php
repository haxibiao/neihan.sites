@php
	$article = $video->article;
@endphp

@if($article)
@if(canEdit($video))
	<div class="pull-right">
	  {!! Form::open(['method' => 'delete', 'route' => ['video.destroy', $video->id], 'class' => 'form-horizontal pull-left']) !!}
	    {!! Form::submit('删除', ['class' => 'btn btn-default btn-small']) !!}                
	  {!! Form::close() !!}
	    <a class="btn btn-primary btn-small" href="/video/{{ $video->id }}/edit" role="button" style="margin-left: 5px">
	        编辑
	    </a>
	</div>
@endif
<div class="media">
	<a class="{{ empty($is_side) ? 'pull-left' : ''}}" href="/video/{{ $video->id }}">
		<img class="media-object" src="{{ $article->cover() }}" alt="{{ $article->title }}" style="height: 150px; width: 200px">
	</a>
	<div class="media-body　strip_title">
		<a href="/video/{{ $video->id }}">
			<h5 class="media-heading">{{ $article->title }}</h5>
		</a>
		<p class="small">更新时间:　{{ $article->updatedAt() }}</p>
		<p title="{{ $article->description() }}">{{ $article->description() }}</p>
	</div>
</div>
@endif
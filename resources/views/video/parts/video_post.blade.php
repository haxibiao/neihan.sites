@php
	$article = $post;
	$video = $post->video;
@endphp

@if(!empty($video))
@if(canEdit($video))
	<div class="pull-right">
	  {!! Form::open(['method' => 'PUT', 'route' => ['video.update', $video->id], 'class' => 'form-horizontal pull-left']) !!}
	  	@if($article->status == 0)
	  	{!! Form::hidden('status', 1) !!}
	    {!! Form::submit('上架', ['class' => 'btn btn-success btn-small']) !!}
	    @else 
	    {!! Form::hidden('status', 0) !!}
	    {!! Form::submit('下架', ['class' => 'btn btn-default btn-small']) !!}
	    @endif
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
		<p>
			@if($article->status == 0) 
			<span class="label label-info">已下架</span>
			@endif
		</p>
	</div>
</div>
@endif
<div class="media">
	@if(!empty($video->cover))
	<a class="{{ empty($is_side) ? 'pull-left' : ''}}" href="/video/{{ $video->id }}">
		<img class="media-object" src="{{ $video->cover }}" alt="{{ $video->title }}" style="max-height: 150px; max-width: 200px">
	</a>
	@endif
	<div class="media-body　strip_title">
		<a href="/video/{{ $video->id }}">
			<h5 class="media-heading">{{ $video->title }}</h5>
		</a>
		<p class="small">更新时间:　{{ $video->updatedAt() }}</p>
		<p title="{{ $video->introduction }}">{{ str_limit($video->introduction, 80) }}</p>
	</div>
</div>
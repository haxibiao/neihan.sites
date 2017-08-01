<div class="media">
	@if(!empty($video->cover))
	<a class="pull-left" href="/video/{{ $video->id }}">
		<img class="media-object" src="{{ get_img($video->cover) }}" alt="{{ $video->title }}">
	</a>
	@endif
	<div class="media-body　strip_title">
		<a href="/video/{{ $video->id }}">
			<h5 class="media-heading">{{ $video->title }}</h5>
		</a>
		<p class="small">更新时间:　{{ diffForHumansCN($video->updated_at) }}</p>
		<p title="{{ $video->introduction }}">{{ str_limit($video->introduction, 80) }}</p>
	</div>
</div>
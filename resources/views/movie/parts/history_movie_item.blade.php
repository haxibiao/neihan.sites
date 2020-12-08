@php
$movie = $historyItem->movie;
@endphp
@if ($movie)
<a class="hs-video_item" href="/movie/{{ $movie->id }}">
    <span class="video_figure">
        <img src="{{ $movie->cover_url }}" alt="" class="video_pic">
        <div class="video_figure_caption">{{ $movie->count_series }}</div>
    </span>
    <span title="摩天大楼 第10集" class="video_title">{{ $movie->name }}</span>
    <span class="video_progress">{{ $historyItem->time_ago }}</span>
</a>
@endif

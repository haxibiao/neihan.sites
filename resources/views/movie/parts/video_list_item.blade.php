@php
$first = $movies->first();
$movies = $movies->forget(0);

@endphp

<div class="video-list-item">
    <div class="panel_head clearfix">
        <h3 class="title">{{ $categoryTitle }}</h3>
        <a class="more" href="/category/{{ $movies->get(1)->region}}">更多<i class="iconfont icon-arrow-right"></i></a>
    </div>
    <div class="panel_body">
        <div class="video_detail clearfix">
            <div class="detail-thumb">
                <a class="video_thumb" style="background: url({{ $first->cover }});" href="movie/{{ $first->id }}"
                    title="{{ $first->name }}">
                </a>
            </div>
            <div class="detail-side">
                <h4 class="title">
                    <a href="movie/{{ $first->id }}">
                        {{ $first->name }}
                    </a>
                </h4>
                <p class="info">
                    <span class="text-muted">类型：</span>喜剧片
                </p>
                <p class="info text-ellipsis">
                    <span class="text-muted">主演：</span>
                        @if(mb_strlen($first->actors) <= 10)
                            {{ $first->actors }}
                        @else
                            {{ mb_substr($first->actors, 0, 10).'...' }}
                        @endif
                </p>
            </div>
        </div>
        <ul class="video_text clearfix">
            @foreach ($movies as $index => $movie)
                <li>
                    <a class="movie-title text-ellipsis" href="/movie/{{ $movie->id }}" title="{{ $movie->name }}">
                        <span class="badge">{{ $index }}</span>{{ $movie->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

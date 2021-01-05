@php
$movieTitle = array_fill(0, 8, '隐秘而伟大');
@endphp

<div class="video-list-item">
    <div class="panel_head clearfix">
        <h3 class="title text-ellipsis">{{ $title }}排行榜</h3>
        {{-- <a class="more" href="/top">更多<i class="iconfont icon-arrow-right"></i></a>
        --}}
    </div>
    <div class="panel_body">
        <ul class="video_text clearfix">
            @foreach ($movieTitle as $item)
                <li class="line">
                    <span class="badge">{{ $loop->index + 1 }}</span>
                    <a class="movie-title text-ellipsis" href="/detail" title={{ $item }}>
                        {{ $item }}
                        <span class="description text-ellipsis">谍战！李易峰金晨乱世蜕变谍战！</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

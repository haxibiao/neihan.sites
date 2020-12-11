<div class="movie-box">
    <a class="movie-thumb lazyload-img" href="/movie/{{ $movie->id }}" target="_blank" title="{{ $movie->name }}"
        style="background-image: url({{ $movie->cover }});">
        <span class="play-icon hidden-xs">
            <i class="iconfont icon-play-fill1"></i>
        </span>
        <span class="pic-tag pic-tag-top" style="background-color: #5bb7fe;">{{ mt_rand(7, 9) }}.0分</span>
        <span class="pic-text pic-bottom">已完结</span>
        {{-- 电视剧（series） --}}
        {{-- <span class="pic-text pic-bottom">更新至第几集 / 已完结</span>
        --}}
        {{-- 电影 (film)--}}
        {{-- <span class="pic-text pic-bottom">HD</span> --}}
    </a>
    <div class="movie-detail">
        <h4 class="title text-ellipsis">
            <a href="/movie/{{ $movie->id }}" target="_blank" title="{{ $movie->name }}">{{ $movie->name }}</a>
        </h4>
        <p class="text text-ellipsis hidden-xs">{{ $movie->actors ?? '主演： ' . $movie->actors }}</p>
    </div>
</div>

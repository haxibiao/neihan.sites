<div class="movie-box">
    <a class="movie-thumb lazyload-img" href="/movie/{{ $movie->id }}" target="_blank" title="匆匆心动"
        style="background-image: url({{ $movie->cover_url }});">
        <span class="play-icon hidden-xs">
            <i class="iconfont icon-play-fill1"></i>
        </span>
        <span class="pic-tag pic-tag-top" style="background-color: #5bb7fe;">8.0分</span>
        <span class="pic-text pic-bottom">已完结</span>
        {{-- 电视剧（series） --}}
        {{-- <span class="pic-text pic-bottom">更新至第几集 / 已完结</span> --}}
        {{-- 电影 (film)--}}
        {{-- <span class="pic-text pic-bottom">HD</span> --}}
    </a>
    <div class="movie-detail">
        <h4 class="title text-ellipsis">
            <a href="/play" target="_blank" title="{{ $movie->name }}">{{ $movie->name }}</a>
        </h4>
        <p class="text text-ellipsis hidden-xs">主演：{{ $movie->actors }}</p>
    </div>
</div>

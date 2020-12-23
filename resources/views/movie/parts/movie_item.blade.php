<div class="movie-box">
    <a class="movie-thumb lazyload-img" href="/movie/{{ $movie->id }}"   title="{{ $movie->name }}"
        style="background-image: url({{ $movie->cover }});">
        <span class="play-icon hidden-xs">
            <i class="iconfont icon-play-fill1"></i>
        </span>
        <span class="pic-tag pic-tag-top" style="background-color: #5bb7fe;">8.0分</span>
        <span class="pic-text pic-bottom">已完结</span>
    </a>
    <div class="movie-detail">
        <h4 class="title text-ellipsis">
            <a href="/play"   title="{{ $movie->name }}">{{ $movie->name }}</a>
        </h4>
        <p class="text text-ellipsis hidden-xs">主演：{{ $movie->actors }}</p>
    </div>
</div>

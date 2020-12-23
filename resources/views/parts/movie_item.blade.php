<div class="movie-box">
    <a class="movie-thumb lazyload-img" href="/movie/{{ $movie->id }}"   title="{{ $movie->name }}"
        style="display:block;background-image: url({{$movie->cover}});width:150px; height:200px;background-size:cover;">
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
        <h5 class="title text-ellipsis">
            <a href="/movie/{{ $movie->id }}"   title="{{ $movie->name }}">
                @if(mb_strlen($movie->name) <= 10)
                    {{ $movie->name }}
                @else
                    {{ mb_substr($movie->name, 0, 10).'...' }}
                @endif
            </a>
        </h5>
        <!-- <p class="text text-ellipsis hidden-xs">{{ $movie->actors ?? '主演： ' . $movie->actors }}</p> -->
    </div>
</div>

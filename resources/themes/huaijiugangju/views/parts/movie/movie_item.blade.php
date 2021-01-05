@php
$type='最新推荐'
@endphp
<div class="movie-box">
    <a class="movie-thumb lazyload-img" href="/detail" title="那一刻相见[热播]" {{--
        style="background-image: url(//puui.qpic.cn/vcover_vt_pic/0/1kn9vq9l0nqeys01606450549804/350);"
        --}}>
        <img src="//puui.qpic.cn/vcover_vt_pic/0/1kn9vq9l0nqeys01606450549804/350" class="movie-bg_img">
        <span class="play-icon hidden-xs">
            <i class="iconfont icon-play-fill1"></i>
        </span>

        <span class="pic-tag pic-tag-top" style="background-color: #5bb7fe;">8.0分</span>
        <span class="pic-year pic-year-top" style="background-color: #5bb7fe;">2019</span>
        <span class="pic-text pic-bottom pic-episode">已完结</span>
        <span class="pic-text pic-bottom pic-year-bottom">2019</span>
        {{-- 电视剧（series） --}}
        {{-- <span class="pic-text pic-bottom">更新至第几集 / 已完结</span>
        --}}
        {{-- 电影 (film)--}}
        {{-- <span class="pic-text pic-bottom">HD</span>
        --}}
    </a>
    <div class="movie-detail">
        <h4 class="title text-ellipsis">
            <a href="/detail" target="_blank" title="那一刻相见[热播]">那一刻相见[热播]</a>
        </h4>
        @if ($type !== '综艺' && $type !== '明星')
            {{-- 不是综艺、不是明星展示主演 --}}
            <p class="text text-ellipsis hidden-xs">陆毅李一桐"大叔萝莉恋"</p>
        @endif
    </div>
</div>

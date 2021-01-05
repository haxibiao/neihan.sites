@if ($movie['type_name'] != '明星')
    <div class="search-result_video">
        <a class="result-thumb lazyload-img" href="/detail" target="_blank" title="{{ $movie['name'] }}"
            style="background-image: url({{ $movie['cover_url'] }});">
            {{-- <span class="play-icon hidden-xs">
                <i class="iconfont icon-play-fill1"></i>
            </span>
            <span class="pic-tag pic-tag-top" style="background-color: #5bb7fe;">9.0分</span>
            <span class="pic-text pic-bottom">已完结</span> --}}
        </a>
        <div class="result-info">
            <div class="infos">
                <h2 class="video_title">
                    <a href="/detail" target="_blank">
                        <em class="video_name">{{ $movie['name'] }}</em>
                    </a>
                    <span class="video_score">{{ mt_rand(0, 9) . '.' . mt_rand(0, 9) }}</span>
                </h2>
                <div class="video_info">
                    <div class="info_item">
                        <span class="label">导&nbsp;&nbsp;演：</span>
                        @foreach ($movie['producer'] as $item)
                            <span class="content">
                                <a href="javascript:void(0)" target="_blank">{{ $item }}</a>
                            </span>
                        @endforeach
                    </div>
                    <div class="info_item">
                        <span class="label">主&nbsp;&nbsp;演：</span>
                        @foreach ($movie['actors'] as $actor)
                            <span class="content">
                                <a href="/star">{{ $actor }}</a>
                                {{-- <a href="javascript:void(0)" target="_blank">沈磊</a>
                                <a href="javascript:void(0)" target="_blank">程玉珠</a>
                                <a href="javascript:void(0)" target="_blank">黄翔宇</a> --}}
                            </span>
                        @endforeach
                    </div>
                    <div class="info_item">
                        <span class="label">分&nbsp;&nbsp;类：</span>
                        <span class="content"><a href="javascript:void(0)" target="_blank">动漫</a></span>
                    </div>
                    <div class="info_item">
                        <span class="label">集&nbsp;&nbsp;数：</span>
                        <span class="label">更新至第2集</span>
                    </div>
                    {{-- <div class="info_item info_item_desc">
                        <span class="label">简&nbsp;&nbsp;介：</span>
                        <span class="desc_text">
                            {{ $movie['introduction'] }}
                            <a class="desc_more" href="/detail">
                                详细<i class="iconfont icon-arrow-right"></i>
                            </a>
                        </span>
                    </div> --}}
                    <div class="video_operation">
                        <a class="video-btn detail" href="/detail">
                            查看详情&nbsp;<i class="iconfont icon-arrow-right"></i>
                        </a>
                        <a class="video-btn play" href="/play">
                            <i class="iconfont icon-play-fill"></i>&nbsp;立即播放
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col-lg-3 col-md-3  col-sm-4 col-xs-4  padding-0">
        @include('parts/movie.star_item')
    </div>
@endif

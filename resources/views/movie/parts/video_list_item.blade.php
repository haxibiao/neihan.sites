@php
$movieTitle = array_fill(0, 6, '我和我的家乡');
@endphp

<div class="video-list-item">
    <div class="panel_head clearfix">
        <h3 class="title">{{ $cate }}总榜单</h3>
        <a class="more" href="/play">更多<i class="iconfont icon-arrow-right"></i></a>
    </div>
    <div class="panel_body">
        <div class="video_detail clearfix">
            <div class="detail-thumb">
                <a class="video_thumb"
                    style="background: url(https://img.dsyszy.xyz/pic/upload/vod/2020-10/1602003757.jpg);" href="/play"
                    title="我和我的家乡">
                </a>
            </div>
            <div class="detail-side">
                <h4 class="title">
                    <a href="/play">
                        我和我的家乡
                    </a>
                </h4>
                <p class="info">
                    <span class="text-muted">类型：</span>喜剧片
                </p>
                <p class="info margin-0">
                    <span class="text-muted">主演：</span>葛优,黄渤,范伟,邓超
                </p>
            </div>
        </div>
        <ul class="video_text clearfix">
            @foreach($movieTitle as $item)
            <li>
                <a class="movie-title text-ellipsis" href="/play" title="我和我的家乡">
                    <span class="badge">{{$loop->index + 1}}</span>{{$item}}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>

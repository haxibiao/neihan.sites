@php
$hotMovies = array_fill(0, 15, '置顶的热门影视');
@endphp

<div class="hot-movies-intro">
    @foreach($hotMovies as $item)
    <div class="movie-info">
        <h3 class="movie-name">琅琊榜</h3>
        <p class="movie-abstract webkit-ellipsis">
            架空权谋类影视剧，梅长苏（胡歌饰）本远在江湖，却名动帝辇。江湖传言：“江左梅郎，麒麟之才，得之可得天下。”作为天下第一大帮“江左盟”的首领，梅长苏“梅郎”之名响誉江湖。然而，有着江湖至尊地位的梅长苏，却是一个病弱青年、弱不禁风，背负着十多年前巨大的冤案与血海深仇，就连身世背后也隐藏着巨大的秘密。
        </p>
    </div>
    @endforeach
</div>
<div id="hot-movies" class="hot-movies-panel">
    @foreach($hotMovies as $item)
    <a href="/play" target="_blank" class="movie-item">
        <img data-src="/images/movie/setTop/movie_pic_{{$loop->index + 1}}.jpg" alt="" class="movie-pic">
    </a>
    @endforeach
</div>

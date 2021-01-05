@php
$hotMovies = array_fill(0, 12, '置顶的热门影视');
@endphp
<div class="hot-movies-intro">
    @foreach ($hotMovies as $movie)
        <div class="movie-info">
            <h3 class="movie-name">太阳的后裔</h3>
            <p class="movie-abstract webkit-ellipsis">
                特战警备队大尉柳时镇（宋仲基 饰）与上士徐大荣（晋久 饰）休假之时遭遇激斗事件，送小偷去医院的时候，被主任医师姜暮烟（宋慧乔 饰）误会，也引得徐大荣的前女友尹明珠（金智媛
                饰）突然出现，姜暮烟因此与柳时镇结缘，可是由于立场不同最终不欢而散。一次意外派遣，姜暮烟又与柳时镇相遇在战火频发的乌鲁克，作为海外医疗派遣队队长的姜暮烟与柳时镇无数次并肩作战，感情得到了升华。可是回国后姜暮烟面对柳时镇出生入死的工作又开始了新一轮担忧，与此同时，徐大荣与尹明珠的爱情也再次遇到了威胁。《太阳的后裔》是韩国KBS电视台于20

            </p>
        </div>
    @endforeach
</div>
<div id="hot-movies" class="hot-movies-panel">
    @foreach ($hotMovies as $movie)
        <a href="/play" target="_blank" class="movie-item">
            <img data-src="/images/movie/setTop/movie_pic_{{ $loop->index + 1 }}.jpg" alt="" class="movie-pic">
        </a>
    @endforeach
</div>

<div class="video-info module-style">
    <div id="media_module" class="clearfix report-wrap-module">
        <div class="video-cover">
            <img src={{ $Movie['cover_url'] }} alt="">
            <span class="pic-text pic-bottom ">已完结</span>
        </div>
        <div class="video-right">
            <p class="video-title text-ellipsis">{{ $Movie['name'] }}</p>
            <h4 class="score">
                {{ mt_rand(0, 9) . '.' . mt_rand(0, 9) }}
            </h4>
            <div class="pub-wrapper clearfix">
                <div>
                    <p class="text-ellipsis">
                        <a class="pub-info">
                            {{ mt_rand(30, 120) }}.5万播放&nbsp;·&nbsp;1.2万评论&nbsp;·&nbsp;32.8万追剧
                        </a>
                    </p>
                    <p class="text-ellipsis">
                        <span class="pub-info">类型：</span>
                        <a href="/" target="_blank" class="type-link">{{ $Movie['type_name'] }}</a>
                        <span class="split-line"></span>
                        <span class="pub-info">年份：</span>
                        <a href="/" target="_blank" class="home-link">{{ $Movie['year'] }}</a>
                    </p>
                    <p class="text-ellipsis">
                        <span class="pub-info">导演：</span>
                        @foreach ($Movie['directors'] as $director)
                            <a href="/movie/search" target="_blank" class="home-link"
                                title={{ $director }}>{{ $director }}</a>
                        @endforeach
                    </p>
                    <p class="text-ellipsis">
                        <span class="pub-info">主演：</span>
                        @foreach ($Movie['stars'] as $star)
                            <a href="/star/" target="_blank" class="home-link" title={{ $star }}>{{ $star }}</a>
                        @endforeach
                    </p>
                    <p class="text-ellipsis">
                        <span class="pub-info">首播：</span>{{ $Movie['first_time'] }}
                    </p>
                </div>
                <p class="intro">
                    <span class="intro">
                        <label class="pub-info">简介：</label>{{ $Movie['introduction'] }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

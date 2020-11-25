<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ seo_site_name() }}</title>
    <link rel="stylesheet" href="/css/base.css" />
    <link rel="stylesheet" href="/css/index.css" />
</head>

<body>

    <header style="z-index: 99999999;">
        <div class="head-left">
            <img class="logo" src="{{ small_logo() }}">
            <p>{{ seo_site_name() }}</p>
        </div>
        <div class="head-right">
            <a style="display: block; margin-top: .035rem;" onclick="openlink(getDownloadUrl())">打开看看</a>
        </div>

    </header>


    <button id="video_btn"></button>
    <video id="theVideo" class="video-player" src="{{ $video->url }}" preload="auto" type="video/mp4" width="100%"
        webkit-playsinline="true" playsinline="true" x5-video-player-type="h5" x5-video-player-fullscreen="portraint"
        onerror="window.VIDEO_FAILED=1"></video>


    <div id="tancen"></div>
    <div id="xiazaiimg">
        <div id="close" onclick="close()">X</div>
        <a onclick="openlink(getDownloadUrl())">
            <img src="/images/images.png">
        </a>
    </div>
    <!-- 微信环境跳到浏览器提示 -->
    <div id="mask"
        style="position:fixed; z-index:999999999999; top: 0; width: 100%; height: 100%; background: rgba(102, 102, 102, 0.5); display: none;">
        <div style="position:absolute; width: 100%; height: 100%;">
            <img src="/images/big-mask.jpg" style="width :100%; position:absolute;" alt="请通过浏览器打开">
            <span
                style="color: #FFFFFF; width:70%; font-size: 15px; margin: 5% 30% 0 5%; position: relative; top: 30px; left:10%;">点击右上角按钮，然后在弹出的菜单中<br />点击
                "用浏览器打开" 后再下载安装。</span>
        </div>
    </div>



    <div class="video-info" id="videoInfo">
        <a onclick="openlink(getDownloadUrl())">
            <div class="info-right">
                <div class="info-item info-avator" data-item="avator">
                    <img class="img-avator" src="{{ $user->avatar_url }}" />
                    <img class="img-follow" src="/images/icon_home_follow_56dbda0.png" />
                </div>

                <div class="info-item info-like">
                    <img class="icon" src="/images/zan.png" />
                    <p class="count" style="color: #fff;font-size: .1rem;">{{ $article->count_likes }}</p>
                </div>
                <div class="info-item">
                    <img class="icon" src="/images/pinglun.png" />
                </div>
                <div class="info-item">
                    <img class="icon" src="/images/zhuanfa.png" />
                </div>
            </div>
        </a>
        <div class="info-text">
            <p class="bottom-user" style="font-size: .16rem;">@ {{ $user->name }}</p>
            <p class="bottom-desc" style="font-size: .14rem;">{{ $article->body }}</p>
        </div>
    </div>
</body>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="/js/main.js"></script>
<script type="text/javascript">
    function getDownloadUrl() {
        let DownloadLink;
        if (isIos()) {
            DownloadLink = "{{ aso_value('下载页', '苹果地址') }}";
        } else {
            DownloadLink = "{{ aso_value('下载页', '安卓地址') }}";
        }
        return DownloadLink;
    }

</script>

</html>

@extends('layouts.app')
@section('title') 
  {{ $appname }}移动应用 App - {{ $appname }}   
@endsection
@push('section')
  <div class="container-fluid apps">
    <div class="row">
        <div class="container top-part">
          <div class="top-logo">
            <img class="logo" src="/logo/{{ $app }}.com.small.png" alt="app logo">
            <div class="info">
              <div class="title">群衣阁</div>
              <div class="slogan">一个专注气质搭配的平台</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img class="phone-img no-border" src="/images/app/{{ $app }}1.png" alt="app phone">
          <div class="top-qrcode">
            <img src="/qrcode/{{ $app }}.com.png" alt="Download apps page top qrcode">
            <div class="title">扫码下载{{ $appname }}App</div>
            <div class="introduce">随时随地发现和分享内容</div>
          </div>
          <div class="download-phone">
            <a href="http://{{ $app }}-1251052432.cossh.myqcloud.com/{{ $app }}.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
            <a href="https://www.pgyer.com/{{ $app }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
            <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
            <h4>点击下载{{ $appname }}App</h4>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="container middle-part">
            <div class="col-sm-12 col-sm-offset-2 text-block">
              <h3>气质穿搭指南、时尚美妆教程、最新潮流资讯、一网打尽</h3>
              <h6>群衣阁励志让有品位的你拥有更高品质的生活方式，在这里资深编辑每日实时推荐当季最热流行搭配</br>站内有着数千款的独特灵感穿搭分享，让你穿出女神范，解决你“换季没有衣服穿”的世纪难题！</h6>
            </div>
           {{--  <div class="col-sm-12 game-imgs">
              <img src="/images/app/pubg_old.jpg" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/dota2.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/lol_logo1.jpg" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/wangzherongyao_logo.jpg" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/nishuihang_logo.png" alt="Misc pic1" class="game-img" alt="game-logo">
            </div> --}}
          </div>
        </div>
        <div class="row">
            <div class="container middle-part">
              <div class="col-sm-4 col-sm-offset-1"><img class="no-border" src="/images/app/{{ $app }}2.png" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>精选短视频，发现美的生活</h3>
                <h6>最全面的流行穿搭</br>美妆小课堂实时更新</br>有时尚达人教你搭配</br>美妆博主真人种草</br>甜美日系风，性感欧美风，清新韩系风</br>随心所欲让你造型百变无压力。</h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-1 text-block">
              <h3>热门专题，发现你的美</h3>
              <h6>不知道约会穿什么？</br>不知道哪种风格适合你？</br>群衣阁精选专题个性化推送，
              </br>给你最适合最精准的穿搭建议</br>上街刷爆回头率，告别衣橱选择恐惧症！</h6>
            </div>
            <div class="col-sm-4 col-sm-offset-1"><img class="no-border" src="/images/app/{{ $app }}4.png" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-4 col-sm-offset-1"><img class="no-border" src="/images/app/{{ $app }}3.png" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>优质交流社区，分享你的美</h3>
            <h6>资深时尚达人汇聚地</br>随时随地分享你的穿搭风格</br>show出你的品位</br>分享你的购物心得</br>找到和你品位相投的好友一起分享美好生活。</br></h6>
          </div>
        </div>
      </div>
      <div class="row">
      <div class="container bottom-part">
        <div class="download-web">
          <img class="bottom-qrcode" src="/qrcode/{{ $app }}.com.png" alt="Download apps page bottom qrcode">
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <div>扫码下载{{ $appname }}App</div>
        </div>
        <div class="download-phone">
          <a href="http://{{ $app }}-1251052432.cossh.myqcloud.com/{{ $app }}.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
          <a href="https://www.pgyer.com/{{ $app }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ $appname }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="/logo/{{ $app }}.com.small.png" alt="Misc logo">
          <div class="info">
            <div class="title">群衣阁</div>
              <div class="slogan">一个专注气质搭配的平台</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
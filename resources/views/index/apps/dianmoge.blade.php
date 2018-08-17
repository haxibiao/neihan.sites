@extends('layouts.app')
@section('title') 
  {{ config('app.name') }}移动应用 App - {{ config('app.name') }}   
@endsection
@push('section')
  <div class="container-fluid apps">
    <div class="row">
        <div class="container top-part">
          <div class="top-logo">
            <img class="logo" src="/logo/dianmoge.com.small.png" alt="app logo">
            <div class="info">
              <div class="title">点墨阁</div>
              <div class="slogan">随时分享你的快乐瞬间</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img class="phone-img no-border" src="/images/app/{{ $app }}1.jpg" alt="app phone">
          <div class="top-qrcode">
            <img src="/qrcode/dianmoge.com.jpg" alt="Download apps page top qrcode">
            <div class="title">扫码下载{{ env('APP_NAME') }}App</div>
            <div class="introduce">随时随地发现和分享内容</div>
          </div>
          <div class="download-phone">
            <a href="http://dianmoge-1251052432.file.myqcloud.com/dianmoge.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
            <a href="https://www.pgyer.com/dianmoge"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
            <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
            <h4>点击下载{{ env('APP_NAME') }}App</h4>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="container middle-part">
            <div class="col-sm-12 col-sm-offset-2 text-block">
              <h3>实时热搜、搞笑视频、内涵段子、原创文学</h3>
              <h6>你是否想了解最新的热门搜索内容？你是否想观看最搞笑的段子和视频？</br>
              你是否想分享你的原创文学成为作者大大？点墨阁 app一网打尽！你想要我们都有!</h6>
            </div>
          {{--   <div class="col-sm-12 game-imgs">
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
              <div class="col-sm-3 col-sm-offset-2"><img class="no-border" src="/images/app/{{ $app }}2.jpg" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>海量短视频、个性化推送</h3>
                <h6>精彩爆笑的段子，脑洞大开的视频。在这里你可以分享生活趣事、上传游戏精彩瞬间、秀出你的风采、传递开心。</h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-2 text-block">
              <h3>优质内容、独家创造</h3>
              <h6>在这里你可以创造属于你的特色专题内容。记录你的日常有趣瞬间、与共同爱好的玩家分享快乐，让你不再一个人游戏、给你带来不同寻常的邂逅。</h6>
            </div>
            <div class="col-sm-3 col-sm-offset-1"><img class="no-border" src="/images/app/{{ $app }}3.jpg" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-3 col-sm-offset-2"><img class="no-border" src="/images/app/{{ $app }}4.jpg" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>热门游戏交流社区</h3>
            <h6>与大神零距离互动，为信仰站队。</br>
            电竞大神、游戏迷妹的聚集地，创造属于你的游戏世界。</h6>
          </div>
        </div>
      </div>
      <div class="row">
      <div class="container bottom-part">
        <div class="download-web">
          <img class="bottom-qrcode" src="/qrcode/dianmoge.com.jpg" alt="Download apps page bottom qrcode">
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <div>扫码下载{{ env('APP_NAME') }}App</div>
        </div>
        <div class="download-phone">
          <a href="http://dianmoge-1251052432.file.myqcloud.com/dianmoge.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
          <a href="https://www.pgyer.com/dianmoge"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ env('APP_NAME') }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="/logo/dianmoge.com.small.png" alt="Misc logo">
          <div class="info">
            <div class="title">点墨阁</div>
              <div class="slogan">随时分享你的快乐瞬间</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
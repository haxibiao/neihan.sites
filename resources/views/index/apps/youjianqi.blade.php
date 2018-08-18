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
              <div class="title">有剑气</div>
              <div class="slogan">有年轻人有激情的动漫交流平台</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img class="phone-img" src="/images/app/{{ $app }}1.png" alt="app phone">
          <div class="top-qrcode">
            <img src="/qrcode/{{ $app }}.com.jpg" alt="Download apps page top qrcode">
            <div class="title">扫码下载{{ $appname }}App</div>
            <div class="introduce">随时随地发现和分享内容</div>
          </div>
          <div class="download-phone">
            <a href="http://{{ $app }}-1251052432.file.myqcloud.com/{{ $app }}.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
            <a href="https://www.pgyer.com/{{ $app }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
            <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
            <h4>点击下载{{ $appname }}App</h4>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="container middle-part">
            <div class="col-sm-12 col-sm-offset-2 text-block">
              <h3>漫画连载、新番资讯、游戏评测、精彩小说、同人绘画</h3>
              <h6>你是否想了解新番动画的内容及彩蛋？你是否想了解最深度的游戏内容评测？</br>
              你是否想分享你的绘画作品成为触手大大？口袋里的二次元！你想要的我们都有!</h6>
            </div>
            <div class="col-sm-12 game-imgs">
              <img src="/images/app/yjq01.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/yjq02.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/yjq03.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/yjq04.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/yjq05.png" alt="Misc pic1" class="game-img" alt="game-logo">
            </div>
          </div>
        </div>
        <div class="row">
            <div class="container middle-part">
              <div class="col-sm-5 col-sm-offset-1 "><img class="" src="/images/app/{{ $app }}3.png" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>海量短视频、个性化推送</h3>
                <h6>精彩爆笑的段子，脑洞大开的视频</br>在这里你可以分享游戏趣事、上传绘画教程...</br>秀出你的风采、传递二次元正能量</h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-1 text-block">
              <h3>优质内容、独家创造</h3>
              <h6>在这里你可以创造属于你的特色专题内容</br>记录你的日常有趣瞬间、与共同爱好的玩家分享快乐</br>
              让你不再一个人游戏、给你带来不同寻常的邂逅。</h6>
            </div>
            <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/app/{{ $app }}4.png" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/app/{{ $app }}2.png" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>热门动漫交流社区</h3>
            <h6>COS、绘画、手办、JK、LO、轻小说、游戏...</br>玩转各种圈子，零距离互动</br>ACG各领域大神聚集地，创造属于你的二次元世界！</br>快来与我签订契约吧⁄(⁄ ⁄•⁄ω⁄•⁄ ⁄)⁄</h6>
          </div>
        </div>
      </div>
      <div class="row">
      <div class="container bottom-part">
        <div class="download-web">
          <img class="bottom-qrcode" src="/qrcode/{{ $app }}.com.jpg" alt="Download apps page bottom qrcode">
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <div>扫码下载{{ $appname }}App</div>
        </div>
        <div class="download-phone">
          <a href="http://{{ $app }}-1251052432.file.myqcloud.com/{{ $app }}.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
          <a href="https://www.pgyer.com/{{ $app }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ $appname }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="/logo/{{ $app }}.com.small.png" alt="Misc logo">
          <div class="info">
            <div class="title">有剑气</div>
              <div class="slogan">有年轻人有激情的动漫交流平台</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
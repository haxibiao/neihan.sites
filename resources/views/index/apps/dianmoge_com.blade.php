@extends('layouts.app')

@push('section')
  <div class="container-fluid apps">
    <div class="row">
        <div class="container top-part">
          <div class="top-logo">
            <img class="logo" src="/images/appLogo.png" alt="app logo">
            <div class="info">
              <div class="title">创作你的创作</div>
              <div class="slogan">一个优质创作社区</div>
            </div>
          </div>
          <img class="background-img" src="/images/appBackground.png" alt="app background">
          <img class="phone-img" src="/images/apps01.png" alt="app phone">
          <div class="top-qrcode">
            <img src="/qrcode/{{ get_domain() }}.jpg" alt="Download apps page top qrcode">
            <div class="title">扫码下载{{ env('APP_NAME') }}App</div>
            <div class="introduce">随时随地发现和创作内容</div>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="container middle-part">
            <div class="col-sm-6 col-sm-offset-1 text-block">
              <h3>轻松创作精美图文</h3>
              <h6>简单优雅的设计，可以一次上传多张图片、实时保存、多端同步，使创作分享更方便快捷</h6>
            </div>
            <div class="col-xs-12 fix"><img src="/images/apps02.png" alt="Misc pic1"></div>
          </div>
        </div>
        <div class="row">
            <div class="container middle-part">
              <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/apps03.png" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>多元化的创作社区</h3>
                <h6>一篇短文、一首诗、一幅画，在这里，你的创作将得到全世界的赞赏</h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-1 text-block">
              <h3>百万创作者在{{ env('APP_NAME') }}相遇</h3>
              <h6>在{{ env('APP_NAME') }}，仍有数百万创作者在坚持产出优质创作，有数千万读者在用心交流创作；众多精彩创作，只在{{ env('APP_NAME') }}看得到</h6>
            </div>
            <div class="col-sm-6"><img class="" src="/images/apps04.png" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/apps05.png" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>自由地交流和沟通</h3>
            <h6>你可以发表评论、沟通想法。觉得不够？还能给创作者发简信，和无数传遍中文互联网的创作者直接交流</h6>
          </div>
        </div>
      </div>
      <div class="row">
      <div class="container bottom-part">
        <img class="bottom-qrcode" src="/images/downloadApp.png" alt="Download apps page bottom qrcode">
        <img class="background-img" src="/images/appBackground.png" alt="Misc background">
        <div>扫码下载{{ env('APP_NAME') }}App</div>
        <div class="bottom-logo">
          <img src="/images/appLogo.png" alt="Misc logo">
          <div class="info">
            <div class="title">创作你的创作</div>
            <div class="slogan">一个优质创作社区</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
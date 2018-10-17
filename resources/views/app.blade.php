@extends('layouts.app')
@section('title') 
  {{ $appname }}移动应用 App - {{ $appname }}   
@endsection
@push('section')
  <div class="container-fluid apps">
    <div class="row">
        <div class="container top-part">
          <div class="top-logo">
            <img class="logo" src="{{ isset($data['logo']) ? $data['logo'] : '//'.env('APP_DOMAIN').'/logo/'.env('APP_DOMAIN').'.small.png' }}" alt="app logo">
            <div class="info">
              <div class="title">{!! isset($data['h1_title']) ? $data['h1_title'] : '' !!}</div>
              <div class="slogan">{!! isset($data['h1_slogan']) ? $data['h1_slogan'] : '' !!}</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img class="phone-img" src="{{ isset($data['show_images1']) ? $data['show_images1'] : '' }}" alt="app phone">
          <div class="top-qrcode">
            <img src="{{ isset($data['qrcode']) ? $data['qrcode'] : 'https://'.env('APP_DOMAIN').'/qrcode/'.env('APP_DOMAIN').'.png' }}" alt="Download apps page top qrcode">
            <div class="title">{{ isset($data['qrcode_title']) ? $data['qrcode_title'] : '' }}</div>
            <div class="introduce">{{ isset($data['qrcode_slogan']) ? $data['qrcode_slogan'] : '' }}</div>
          </div>
          <div class="download-phone">
            <a href="http://{{ $app }}-1251052432.cosgz.myqcloud.com/{{ $app }}.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
            <a href="https://www.pgyer.com/{{ $app }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
            <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
            <h4>点击下载{{ $appname }}App</h4>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="container middle-part">
            <div class="col-sm-12 col-sm-offset-2 text-block">
              <h3>{!! isset($data['center_title']) ? $data['center_title'] : '' !!}</h3>
              <h6>{!! isset($data['center_slogan']) ? $data['center_slogan'] : '' !!}</h6>
            </div>
            <div class="col-sm-12 game-imgs">
              <img src="/images/app/pubg_old.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/dota2.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/lol-logo1.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/wangzherongyao_logo.png" alt="Misc pic1" class="game-img" alt="game-logo">
              <img src="/images/app/nishuihang_logo.png" alt="Misc pic1" class="game-img" alt="game-logo">
            </div>
            <img src="/images/app/wangzherongyao_logo.png" alt="Misc pic1" class="game" alt="game-logo">
          </div>
        </div>
        <div class="row">
            <div class="container middle-part">
              <div class="col-sm-5 col-sm-offset-1"><img src="{{ isset($data['show_images2']) ? $data['show_images2'] : '' }}" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>{!! isset($data['show_images2_title']) ? $data['show_images2_title'] : '' !!}</h3>
                <h6>{!! isset($data['show_images2_slogan']) ? $data['show_images2_slogan'] : '' !!}</h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-1 text-block">
              <h3>{!! isset($data['show_images3_title']) ? $data['show_images3_title'] : '' !!}</h3>
              <h6>{!! isset($data['show_images3_slogan']) ? $data['show_images3_slogan'] : '' !!}</h6>
            </div>
            <div class="col-sm-5 col-sm-offset-1"><img src="{{ isset($data['show_images3']) ? $data['show_images3'] : '' }}" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-5 col-sm-offset-1"><img src="{{ isset($data['show_images4']) ? $data['show_images4'] : '' }}" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>{!! isset($data['show_images4_title']) ? $data['show_images4_title'] : '' !!}</h3>
            <h6>{!! isset($data['show_images4_slogan']) ? $data['show_images4_slogan'] : '' !!}</h6>
          </div>
        </div>
      </div>
      <div class="row">
      <div class="container bottom-part">
        <div class="download-web">
          <img class="bottom-qrcode" src="{{ isset($data['qrcode']) ? $data['qrcode'] : '' }}" alt="Download apps page bottom qrcode">
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <div>{{ isset($data['qrcode_title']) ? $data['qrcode_title'] : '' }}</div>
        </div>
        <div class="download-phone">
          <a href="http://{{ $app }}-1251052432.cosgz.myqcloud.com/{{ $app }}.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
          <a href="https://www.pgyer.com/{{ $app }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ $appname }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="{{ isset($data['logo']) ? $data['logo'] : '' }}" alt="Misc logo">
          <div class="info">
            <div class="title">{!! isset($data['h1_title']) ? $data['h1_title'] : '' !!}</div>
              <div class="slogan">{!! isset($data['h1_slogan']) ? $data['h1_slogan'] : '' !!}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
@extends('layouts.app')
@section('title') 
  {{ config('app.name_cn') }}移动应用 App - {{ config('app.name_cn') }}   
@endsection
@push('section')
  <div class="container-fluid apps">
    <div class="row">
        <div class="container top-part">
          <div class="top-logo">
            <img class="logo" src="{{ small_logo() }}" alt="app logo">
            <div class="info">
              <div class="title">{{ config('app.name_cn') }} </div>
              <div class="slogan">{!! get_seo_title() !!}</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img class="phone-img" src="{{ aso_value('下载页','功能介绍1图') }}" alt="app phone">
          <div class="top-qrcode">
            <img src=data:image/png;base64,{{ qrcode_url() }} alt="Download apps page top qrcode">
            <div class="title">扫码下载{{ config('app.name_cn') }}</div>
          </div>
          <div class="download-phone">
            <a href="{{ aso_value('下载页','安卓地址') }}"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
            <a href="{{ aso_value('下载页','苹果地址') }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
            <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
            <h4>点击下载{{ config('app.name_cn') }}App</h4>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="container middle-part">
            <div class="col-sm-12 col-sm-offset-2 text-block">
              <h3>{!! aso_value('下载页','功能介绍1标题') !!}</h3>
              <h6>{!! aso_value('下载页','功能介绍1文字') !!}</h6>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="container middle-part">
              <div class="col-sm-5 col-sm-offset-1"><img src="{{ aso_value('下载页','功能介绍2图') }}" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>{!! aso_value('下载页','功能介绍2标题') !!}</h3>
                <h6>{!! aso_value('下载页','功能介绍2文字') !!}</h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-1 text-block">
              <h3>{!! aso_value('下载页','功能介绍3标题') !!}</h3>
              <h6>{!! aso_value('下载页','功能介绍3文字') !!}</h6>
            </div>
            <div class="col-sm-5 col-sm-offset-1"><img src="{{ aso_value('下载页','功能介绍3图') }}" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-5 col-sm-offset-1"><img src="{{ aso_value('下载页','功能介绍4图') }}" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>{!! aso_value('下载页','功能介绍4标题') !!}</h3>
            <h6>{!! aso_value('下载页','功能介绍4文字') !!}</h6>
          </div>
        </div>
      </div>
      <div class="row">
      <div class="container bottom-part">
        <div class="download-web">
          <img class="bottom-qrcode" src=data:image/png;base64,{{ qrcode_url() }} alt="Download apps page bottom qrcode">
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <div>扫码下载{{ config('app.name_cn') }}</div>
        </div>
        <div class="download-phone">
          <a href="{{ aso_value('下载页','安卓地址') }}"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
          <a href="{{ aso_value('下载页','苹果地址') }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ config('app.name_cn') }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="{{ small_logo() }}" alt="Misc logo">
          <div class="info">
            <div class="title">{{ config('app.name_cn') }}</div>
              <div class="slogan">{!! get_seo_title() !!}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
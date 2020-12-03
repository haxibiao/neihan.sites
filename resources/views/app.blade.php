@extends('layouts.app')
@section('title')
  {{ seo_site_name() }}移动应用 App - {{ seo_site_name() }}
@endsection
@push('section')

  <div id="mask" style="position:fixed; z-index:999999999999; top: 0; width: 100%; height: 100%; background: rgba(102, 102, 102, 0.5); display: none;">
    <div style="position:absolute; width: 100%; height: 100%;">
        <img src="/images/app/big-mask.jpg" style="width :100%; position:absolute;" alt="请通过浏览器打开">
        <span style="color: #FFFFFF; width:70%; font-size: 15px; margin: 5% 30% 0 5%; position: relative; top: 30px; left:10%;">点击右上角按钮，然后在弹出的菜单中<br/>点击 "用浏览器打开" 后再下载安装。</span>
    </div>
    </div>
    <div id = "tancen" style="position:relative; top:600px;"></div>

  <div class="container-fluid apps">
    <div class="row">
        <div class="container top-part">
          <div class="top-logo">
            <img class="logo" src="{{ seo_small_logo() }}" alt="app logo">
            <div class="info">
              <div class="title">{{ seo_site_name() }} </div>
              <div class="slogan">{!! get_seo_title() !!}</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img id = "tancen" class="phone-img" src="{{ aso_value('下载页','功能介绍1图') }}" alt="app phone">
          <div class="top-qrcode">
            <img src=data:image/png;base64,{{ qrcode_url() }} alt="Download apps page top qrcode">
            <div class="title">扫码下载{{ seo_site_name() }}</div>
          </div>
          <div class="download-phone">



            <!-- <input  class="download2" type="button"  onclick="download_btn()" style="background:url('/images/app/android_app.png') no-repeat;" />
            <a href="{{ aso_value('下载页','安卓地址') }}"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a> -->
            <img src="/images/app/android_app.png"  onclick="download_btn()" class="download2" alt="download-andorid">



            <a href="{{ aso_value('下载页','苹果地址') }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
            <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
            <h4>点击下载{{ seo_site_name() }}App</h4>
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
          <div>扫码下载{{ seo_site_name() }}</div>
        </div>
        <div class="download-phone">
          <img src="/images/app/android_app.png"  onclick="download_btn()" class="download2" alt="download-andorid">
          <a href="{{ aso_value('下载页','苹果地址') }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ seo_site_name() }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="{{ seo_small_logo() }}" alt="Misc logo">
          <div class="info">
            <div class="title">{{ seo_site_name() }}</div>
              <div class="slogan">{!! get_seo_title() !!}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
@push('scripts')
  <script type="text/javascript">
    if(checkIsTenCent()){
      document.getElementById("mask").style.display="inline";
    }
    function download_btn(){
      if(checkIsTenCent()){
        document.getElementById("mask").style.display="inline";
      } else {
        window.location.href="{{ getDownloadUrl() }}";
      }
    }
    function checkIsTenCent(){
      var ua = navigator.userAgent.toLowerCase();
      return !!(ua.match(/MicroMessenger\/[0-9]/i) || ua.match(/QQ\/[0-9]/i));
    }
  </script>
@endpush







<!-- <script type="text/javascript">
    function download_btn(){

        var ua = navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger\/[0-9]/i )||ua.match(/QQ\/[0-9]/i) ){

            document.getElementById("mask").style.display="inline";

        } else {

            window.location.href="{{ env('APK_URL') }}";
        }
    }


	function download2_btn(){

        var ua = navigator.userAgent.toLowerCase();
        if(request()->path() == "app"){
			if(ua.match(/MicroMessenger\/[0-9]/i )||ua.match(/QQ\/[0-9]/i) ){

                document.getElementById("mask").style.display="inline";

                } else {

                   window.location.href="{{ env('APK_URL')}}";
                }

          window.location.href="{{ "/app" }}";
        }else{
			window.location.href="{{ "/app" }}";
        }
    }

</script> -->

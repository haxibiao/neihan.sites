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
              <div class="title">懂点医</div>
              <div class="slogan">做你身边的健康小助手</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img class="phone-img" src="/images/app/{{ $app }}2.png" alt="app phone">
          <div class="top-qrcode">
            <img src="/qrcode/{{ $app }}.com.jpg" alt="Download apps page top qrcode">
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
              <h3>居家养生、孕妇膳食、婴儿安全、中医养生</h3>
              <h6>专业提供优质的医疗健康科普,包括养生,保健,健康科普,妇科,育儿,孕期,减肥</br>
              急救中医等多方面,全方位为您的健康保驾护航</h6>
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
              <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/app/{{ $app }}5.png" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>专业问答平台、为你答疑解惑</h3>
                <h6>'病急切忌乱投医'，问问大家更靠谱</br>懂点医提供给大家专业问答平台</br>在这里您可以提出问题</br>更有热心的网友为您解答
               </h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-1 text-block">
              <h3>优质内容、独家创造</h3>
              <h6>在这里你可以创造属于你的特色专题内容</br>如果您是精通医学知识的大触</br>懂点医欢迎您为大众分享专业健康知识</br>提高大众的健康素养</h6>
            </div>
            <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/app/{{ $app }}3.png" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/app/{{ $app }}1.png" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>专业医疗健康知识科普平台</h3>
            <h6>你还在为搜索健康知识太麻烦而烦恼？</br>你在为网络上健康知识信息真假难辨而纠结？</br>健康知识一抓一大把，该信还是不该信？</br>加入懂点医！</br>满足大众及时了解专业的健康知识的需求</br>专业问答平台获取健康知识</br>降低民众盲目跟从的风险</br>提高大众的健康素养。
            </h6>
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
          <a href="http://{{ $app }}-1251052432.cossh.myqcloud.com/{{ $app }}.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
          <a href="https://www.pgyer.com/{{ $app }}"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ $appname }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="/logo/{{ $app }}.com.small.png" alt="Misc logo">
          <div class="info">
            <div class="title">懂点医</div>
              <div class="slogan">做你身边的健康小助手</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
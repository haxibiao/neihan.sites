@extends('layouts.app')
@section('title') 
  {{ config('app.name') }}移动应用 App - {{ config('app.name') }}   
@endsection
@push('section')
  <div class="container-fluid apps">
    <div class="row">
        <div class="container top-part">
          <div class="top-logo">
            <img class="logo" src="/logo/dongdianyao.com.small.png" alt="app logo">
            <div class="info">
              <div class="title">懂点药</div>
              <div class="slogan">专业提供中西药大全中西药保健及用法与问答互动平台</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img class="phone-img no-border" src="/images/app/{{ $app }}1.jpg" alt="app phone">
          <div class="top-qrcode">
            <img src="/qrcode/dongdianyao.com.jpg" alt="Download apps page top qrcode">
            <div class="title">扫码下载{{ env('APP_NAME') }}App</div>
            <div class="introduce">随时随地发现和分享内容</div>
          </div>
          <div class="download-phone">
            <a href="http://dongdianyao-1251052432.file.myqcloud.com/dongdianyao.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
            <a href="https://www.pgyer.com/dongdianyao"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
            <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
            <h4>点击下载{{ env('APP_NAME') }}App</h4>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="container middle-part">
            <div class="col-sm-12 col-sm-offset-2 text-block">
              <h3>做你身边的药品小助手</h3>
              <h6>懂点药是一款涵盖中药、西药、健康养生知识等为一体的产品，旨在为用户提供全面易懂的药品知识。</br>
              </h6>
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
              <div class="col-sm-3 col-sm-offset-1"><img class="no-border" src="/images/app/{{ $app }}2.jpg" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>各种药类专题</h3>
                <h6>懂点药对中西药进行了详细分类，不管你是需要了解驱寒药还是清热药或者养生茶，在这里你能搜到任何你想要的中西药相关知识。</h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-1 text-block">
              <h3>有问必答</h3>
              <h6>这里有完善的问答系统，你感到困惑的问题，在这里都能得到回答</br>你可以设置一定的悬赏金额来吸引专业人员给你解答</br>当然，若你能解答其他人的问题</br>你也能回答其他人的问题来获得相应的奖赏。

</h6>
            </div>
            <div class="col-sm-3 col-sm-offset-1"><img class="no-border" src="/images/app/{{ $app }}3.jpg" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-3 col-sm-offset-1"><img class="no-border" src="/images/app/{{ $app }}4.jpg" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>离你最近的健康助手</h3>
            <h6>在懂点药APP里，你能直接与专家进行私聊</br>
            即时通讯功能为你省去很多去医院挂号或者去药店询问的时间</h6>
          </div>
        </div>
      </div>
      <div class="row">
      <div class="container bottom-part">
        <div class="download-web">
          <img class="bottom-qrcode" src="/qrcode/dongdianyao.com.jpg" alt="Download apps page bottom qrcode">
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <div>扫码下载{{ env('APP_NAME') }}App</div>
        </div>
        <div class="download-phone">
          <a href="http://dongdianyao-1251052432.file.myqcloud.com/dongdianyao.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
          <a href="https://www.pgyer.com/dongdianyao"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ env('APP_NAME') }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="/logo/dongdianyao.com.small.png" alt="Misc logo">
          <div class="info">
            <div class="title">懂点药</div>
              <div class="slogan">专业提供中西药大全中西药保健及用法与问答互动平台</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
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
              <div class="title">聚车社  每天分享一点菜谱和美食</div>
              <div class="slogan">懂你的,才是美味</div>
            </div>
          </div>
          <img class="background-img" src="/images/app/appBackground.png" alt="app background">
          <img class="phone-img" src="/images/app/{{ $app }}1.png" alt="app phone">
          <div class="top-qrcode">
            <img src="/qrcode/{{ $app }}.com.png" alt="Download apps page top qrcode">
            <div class="title">扫码下载{{ $appname }}App</div>
            <div class="introduce">随时随地发现和分享内容</div>
          </div>
          <div class="download-phone">
            <a href="http://{{ $app }}-1251052432.cossh.myqcloud.com/{{ $app }}.apk"><img src="/images/app/android_app.png" class="download2" alt="download-andorid"></a>
            <a href="https://itunes.apple.com/cn/app/%E6%87%82%E7%BE%8E%E5%91%B3/id1433177839?mt=8"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
            <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
            <h4>点击下载{{ $appname }}App</h4>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="container middle-part">
            <div class="col-sm-12 col-sm-offset-2 text-block">
              <h3>家常菜谱、合理膳食、美食知识、美食攻略</h3>
              <h6>一款最新潮的美食软件如果您能拥有，您还会有找不到的菜谱吗?</br>
                 拥有海量的优质原创美食菜谱，每篇菜谱都有详细的制作过程图片及文字说明</br>
                您还会担心有学不会的菜吗? 针对各种人群制作合理饮食，教你 吃出健康。在聚车社，带你吃遍天下</h6>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="container middle-part">
              <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/app/{{ $app }}2.png" alt="Misc pic2"></div>
              <div class="col-sm-5 col-sm-offset-1 text-block">
                <h3>海量短视频、个性化推送</h3>
                <h6>视频菜谱、爆笑段子、实事资讯</br>在这里你可以边学习边分享生活趣事</br>上传每一个精彩瞬间</br>秀出你的风采、传递开心。</h6>
              </div>
            </div>
        </div>
        <div class="row">
          <div class="container middle-part">
            <div class="col-sm-5 col-sm-offset-1 text-block">
              <h3>优质内容、独家创造</h3>
              <h6>在这里你可以创造属于你的特色专题内容</br>分享你的美食心得，记录你的日常有趣瞬间</br>与共同爱好的用户分享快乐</br>让每一种美食变得有温度。</h6>
            </div>
            <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/app/{{ $app }}3.png" alt="Misc pic3"></div>
          </div>
        </div>
        <div class="row">
        <div class="container middle-part">
          <div class="col-sm-5 col-sm-offset-1"><img class="" src="/images/app/{{ $app }}4.png" alt="Misc pic4"></div>
          <div class="col-sm-5 col-sm-offset-1 text-block">
            <h3>美食问答及互动社区</h3>
            <h6>结交来自五湖四海的吃货盟友</br>与厨艺界大神零距离互动</br>
            聚集千万美食爱好者</br>创造属于你的美食空间</br>菜谱、实时动态、随拍</br>一起玩的吃货俱乐部。</h6>
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
          <a href="https://itunes.apple.com/cn/app/%E6%87%82%E7%BE%8E%E5%91%B3/id1433177839?mt=8"><img src="/images/app/ios_app.png" class="download2" alt="download-ios"></a>
          <img class="background-img" src="/images/app/appBackground.png" alt="Misc background">
          <h4>点击下载{{ $appname }}App</h4>
        </div>
        <div class="bottom-logo">
          <img src="/logo/{{ $app }}.com.small.png" alt="Misc logo">
          <div class="info">
            <div class="title">聚车社  每天分享一点菜谱和美食</div>
              <div class="slogan">懂你的,才是美味</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endpush
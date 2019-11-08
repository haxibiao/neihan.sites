<div class="download-app">
<a target="{{ isDeskTop()? '_blank':'_self' }}" class="app" href="app" data-toggle="popover" data-placement="top" data-html="true" data-trigger="hover" 
data-content="<img src=data:image/png;base64,{{ qrcode_url() }}">
  <img src=data:image/png;base64,{{ qrcode_url() }} alt="下载{{ config('app.name_cn') }}手机App">
  <div class="app-info">
    <div class="down">下载{{ config('app.name_cn') }}手机App <i class="iconfont icon-youbian"></i></div>
    <div class="describe">随时随地发现和创作内容</div>
  </div>
</a>
</div>
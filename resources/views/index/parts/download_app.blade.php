<div class="download-app">
<a target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" class="app" href="javascript:;" data-toggle="popover" data-placement="top" data-html="true" data-trigger="hover" 
data-content="<img src=/qrcode/{{ get_domain() }}.jpg>">
  <img src="/qrcode/{{ get_domain() }}.jpg" alt="下载{{ config('app.name') }}手机App">
  <div class="app-info">
    <div class="down">下载{{ config('app.name') }}手机App <i class="iconfont icon-youbian"></i></div>
    <div class="describe">随时随地发现和创作内容</div>
  </div>
</a>
</div>
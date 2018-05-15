<div class="board">
   <a href="/trending?type=new">
    <img src="/images/board01.png" alt=""><span class="board_tit one">新上榜 <i class="iconfont icon-youbian"></i></span>
   </a>
   <a href="/trending?type=seven">
    <img src="/images/board02.png" alt=""><span class="board_tit two">7日热门 <i class="iconfont icon-youbian"></i></span>
   </a>
   <a href="/trending?type=thirty">
    <img src="/images/board03.png" alt=""><span class="board_tit three">30日热门 <i class="iconfont icon-youbian"></i></span></a>
  {{--  <a href="#"><img src="/images/board04.png" alt=""><span class="board_tit four">{{ config('app.name') }}出版 <i class="iconfont icon-youbian"></i></span></a> --}}
   
   @php
    $domain_key = str_replace('.','_',get_domain());
   @endphp
   
   @if(file_exists(app_path("/resource/views/index/parts/special/$domain_key.blade.php")))
    @include('index.parts.special.'. $domain_key)
   @endif

</div>
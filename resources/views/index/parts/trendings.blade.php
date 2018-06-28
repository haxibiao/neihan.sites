<div class="board">
   <a href="/trending" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
    <img src="/images/board01.png" alt=""><span class="board_tit one">经典热门 <i class="iconfont icon-youbian"></i></span>
   </a>
   <a href="/trending?type=seven" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
    <img src="/images/board02.png" alt=""><span class="board_tit two">7日热门 <i class="iconfont icon-youbian"></i></span>
   </a>
   <a href="/trending?type=thirty" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
    <img src="/images/board03.png" alt=""><span class="board_tit three">30日热门 <i class="iconfont icon-youbian"></i></span></a>
   
   @if(file_exists(resource_path("/views/index/parts/special/". get_domain_key() .".blade.php")))
      @include('index.parts.special.'. get_domain_key())
   @endif

</div>
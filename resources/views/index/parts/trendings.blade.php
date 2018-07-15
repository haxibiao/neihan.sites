<div class="board">
  <div class="board-list">
    <a class="permanent" href="/trending" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
      <span class="board-title">经典热门<i class="iconfont icon-youbian"></i></span>
      <i class="iconfont icon-paihangbang board-right"></i>
    </a>
  </div>
   <div class="board-list">
    <a class="seven-days" href="/trending?type=seven" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
      <span class="board-title">七日热门<i class="iconfont icon-youbian"></i></span>
      <i class="iconfont icon-hot board-right"></i>
    </a>
  </div>
   <div class="board-list">
    <a class="thirty-days" href="/trending?type=thirty" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
      <span class="board-title">30日热门<i class="iconfont icon-youbian"></i></span>
      <i class="iconfont icon-HOT board-right"></i>
    </a>
  </div>
   <div class="board-list">
    <a class="question" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/question">
      <span class="board-title">爱你城问答<i class="iconfont icon-youbian"></i></span>
      <i class="iconfont icon-changjianwenti board-right"></i>
    </a>
  </div>
   @if(file_exists(resource_path("/views/index/parts/special/". get_domain_key() .".blade.php")))
      @include('index.parts.special.'. get_domain_key())
   @endif

</div>
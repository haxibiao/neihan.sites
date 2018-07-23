<div class="recommend-category hidden-xs">
 
  @foreach($data->categories as $category)
    <a target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/{{ $category->name_en }}" class="category-label">
      <img src="{{ $category->smallLogo() }}" alt="{{ $category->name }}">
      <span class="name">{{ $category->name }}</span>
    </a>
  @endforeach

  <a target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/category" class="category-label more">
    <span class="name">更多热门专题 <i class="iconfont icon-youbian"></i></span>
  </a>
</div> 
<div class="recommend-category hidden-xs">
 
  @foreach($data->categories as $category)
    <a target="{{ isDeskTop()? '_blank':'_self' }}" href="/category/{{ $category->id }}" class="category-label">
      <img src="{{ $category->iconUrl }}" alt="{{ $category->name }}">
      <span class="name">{{ $category->name }}</span>
    </a>
  @endforeach

  <a target="{{ isDeskTop()? '_blank':'_self' }}" href="/category" class="category-label more">
    <span class="name">更多热门专题 <i class="iconfont icon-youbian"></i></span>
  </a>
</div> 
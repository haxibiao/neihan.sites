<div class="recommend-category hidden-xs">
  @foreach($categories as $category)
    <a href="/category/{{ $category->id }}" class="category-label">
      <img src="{{ $category->iconUrl }}" alt="{{ $category->name }}">
      <span class="name">{{ $category->name }}</span>
    </a>
  @endforeach
  <a href="/category" class="category-label more">
    <span class="name">更多热门专题 <i class="iconfont icon-youbian"></i></span>
  </a>
</div> 
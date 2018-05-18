<div class="recommend-question">
  <a href="/question" class="question-label {{ request()->path() == 'question' && empty(request('cid')) ? 'active' : '' }} hot">
    <img src="/images/hot.small.jpg" alt="">
    <span class="name">热门</span>
  </a>
  <a href="/question-bonused" class="question-label {{ request()->path() == 'question-bonused' ? 'active' : '' }} money">
    <img src="/images/money.small.jpg" alt="">
    <span class="name">付费</span>
  </a>

  @foreach($categories as $category)
  <a href="/question?cid={{ $category->id }}" class="question-label {{ request('cid') == $category->id ? 'active' : '' }}">
    <img src="{{ $category->smallLogo() }}" alt="">
    <span class="name">{{ $category->name }}</span>
  </a>
  @endforeach
  
  <a href="/categories-for-question" class="question-label more">
    <span class="name">更多分类<i class="iconfont icon-youbian"></i></span>
  </a>
</div>
<li class="article-item {{ $article->hasImage() ? 'have-img' : '' }}">
  @if($article->hasImage())
    <a class="wrap-img" href="{{ $article->content_url() }}" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
        <img src="{{ $article->primaryImage() }}" alt="{{$article->title}}"> 
    </a> 
  @endif 
  <div class="content">
    <div class="author hidden-xs">
      <a class="avatar" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/user/{{ $article->user->id }}">
        <img src="{{ $article->user->avatar() }}" alt="">
      </a> 
      <div class="info">
        <a class="nickname" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/user/{{ $article->user->id }}">{{ $article->user->name }}</a>
        @if($article->user->is_editor)
          <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
        @endif
        <span class="time">{{ $article->updatedAt() }}</span>
      </div>
    </div>
    <a class="title" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="{{ $article->content_url() }}">
        <span>{{ $article->title }}</span>
    </a>
    <a class="abstract" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="{{ $article->content_url() }}">
      {{ $article->description() }}
    </a>
    <div class="meta">
      @if($article->category)
        <a class="collection-tag" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/{{ $article->category->name_en }}">{{ $article->category->name }}</a>
      @endif
      <a target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="{{ $article->content_url() }}">
        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
      </a>
      <a target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="{{ $article->content_url() }}/#comments">
        <i class="iconfont icon-svg37"></i> {{ $article->count_replies }}
      </a>
      <span><i class="iconfont icon-03xihuan"></i> {{ $article->count_likes }} </span>
      @if($article->count_tips)
        <span><i class="iconfont icon-qianqianqian"></i> {{ $article->count_tips }}</span>
      @endif
    </div>
  </div>
</li>
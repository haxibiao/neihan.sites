<li class="article-item {{ $article->hasImage() ? 'have-img' : '' }}">
  @if($article->hasImage())
    <a class="wrap-img" href="/article/{{ $article->id }}" target="_blank">
        <img src="{{ $article->primaryImage() }}" alt="{{$article->title}}">
    </a>
  @endif
  <div class="content">
    <div class="author hidden-xs">
      <a class="avatar" target="_blank" href="/user/{{ $article->user->id }}">
        <img src="{{ $article->user->avatar() }}" alt="">
      </a>
      <div class="info">
        <a class="nickname" target="_blank" href="/user/{{ $article->user->id }}">{{ $article->user->name }}</a>
        @if($article->user->is_editor)
          <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
        @endif
        <span class="time">{{ $article->updatedAt() }}</span>
      </div>
    </div>
    <a class="title" target="_blank" href="/article/{{ $article->id }}">
        <span>{{ $article->title }}</span>
    </a>
    <a class="abstract" target="_blank" href="/article/{{ $article->id }}">
      {{ $article->description() }}
    </a>
    <div class="meta">
      @if($article->category)
        <a class="collection-tag" target="_blank" href="/{{ $article->category->name_en }}">{{ $article->category->name }}</a>
      @endif
      <a target="_blank" href="/article/{{ $article->id }}">
        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
      </a>
      <a target="_blank" href="/article/{{ $article->id }}/#comments">
        <i class="iconfont icon-svg37"></i> {{ $article->count_replies }}
      </a>
      <span><i class="iconfont icon-03xihuan"></i> {{ $article->count_likes }} </span>
      @if($article->count_tips)
        <a><i class="iconfont icon-qianqianqian"></i> {{ $article->count_tips }}</a>
      @endif
    </div>
  </div>
</li>
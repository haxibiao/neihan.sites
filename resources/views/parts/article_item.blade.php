<li class="content-item {{ $article->hasImage() ? 'have-img' : '' }}">
  @if($article->hasImage())
    <a class="wrap-img" href="{{ $article->content_url() }}"   target="_blank">
        <img src="{{ $article->primaryImage() }}" alt="{{$article->title}}">
        @if( $article->type=='video' )
        <span class="rotate-play">
          <i class="iconfont icon-shipin"></i>
        </span>
        @endif
        @if( $article->type=='video' )
        <i class="duration">@sectominute($article->video->duration)</i>
        @endif
    </a>
  @endif
  <div class="content">
    @if( $article->type!=='article' )
    <div class="author">
      <a class="avatar" target="_blank" href="/user/{{ $article->user->id }}">
        <img src="{{ $article->user->avatar() }}" alt="">
      </a>
      <div class="info">
        <a class="nickname" target="_blank" href="/user/{{ $article->user->id }}">{{ $article->user->name }}</a>
        @if($article->user->is_signed)
          <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
        @endif
        @if($article->user->is_editor)
          <img class="badge-icon" src="/images/editor.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}小编" alt="">
        @endif
        <span class="time">{{ $article->updatedAt() }}</span>
      </div>
    </div>
    @endif

    {{-- 如果是文章，就显示标题 --}}
    @if( $article->type=='article' )
    <a class="title" target="_blank" href="{{ $article->content_url() }}">
        <span>{{ $article->title }}</span>
    </a>
    @endif

    {{-- 然后任何类型，这段简介是一定要显示的 --}}
    <a class="abstract" target="_blank" href="{{ $article->content_url() }}">
      {{ $article->get_description() }}
    </a>

    <div class="meta">
      @if($article->category)
        <a class="category" target="_blank" href="/{{ $article->category->name_en }}">
          <i class="iconfont icon-zhuanti1"></i>
          {{ $article->category->name }}
        </a>
      @endif
      @if( $article->type=='article' )
        <a class="nickname" target="_blank" href="/user/{{ $article->user->id }}">{{ $article->user->name }}</a>
      @endif
      <a target="_blank" href="{{ $article->content_url() }}">
        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
      </a>
      <a target="_blank" href="{{ $article->content_url() }}/#comments">
        <i class="iconfont icon-svg37"></i> {{ $article->count_replies }}
      </a>
      <span><i class="iconfont icon-03xihuan"></i> {{ $article->count_likes }} </span>
      @if($article->count_tips)
        <span class="hidden-xs" ><i class="iconfont icon-qianqianqian"></i> {{ $article->count_tips }}</span>
      @endif
    </div>
  </div>
</li>
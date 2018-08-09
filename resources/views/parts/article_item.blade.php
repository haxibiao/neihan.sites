<li class="content-item {{ $article->hasImage() ? 'have-img' : '' }}">
  @if($article->hasImage())
    <a class="wrap-img" href="{{ $article->content_url() }}"   target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
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
    @endif
    @if( $article->type=='article' )
    <a class="title" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="{{ $article->content_url() }}">
        <span>{{ $article->title }}</span>
    </a>
    @endif
    <a class="abstract" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="{{ $article->content_url() }}">
      {{ $article->get_description()?$article->get_description():$article->title }}
    </a>
    <div class="meta">
      @if($article->category)
        <a class="category" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/{{ $article->category->name_en }}">
          <i class="iconfont icon-zhuanti1"></i>
          {{ $article->category->name }}
        </a>
      @endif
      @if( $article->type=='article' )
        <a class="nickname" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/user/{{ $article->user->id }}">{{ $article->user->name }}</a>
      @endif
      <a class="hidden-xs" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="{{ $article->content_url() }}">
        <i class="iconfont icon-liulan"></i> {{ $article->hits }}
      </a>
      <a target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="{{ $article->content_url() }}/#comments">
        <i class="iconfont icon-svg37"></i> {{ $article->count_replies }}
      </a>
      <span><i class="iconfont icon-03xihuan"></i> {{ $article->count_likes }} </span>
      @if($article->count_tips)
        <span class="hidden-xs" ><i class="iconfont icon-qianqianqian"></i> {{ $article->count_tips }}</span>
      @endif
    </div>
  </div>
</li>
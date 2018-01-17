@if(!empty($articles))
{{-- 发布 --}}
@foreach($articles as $article)
<li class="article_item have-img">
  <a class="wrap_img" href="/article/{{ $article->id }}" target="_blank">
      <img src="{{ $article->primaryImage() }}" alt="">
  </a>
  <div class="content">
    <div class="author">
      <a class="avatar" target="_blank" href="/user/{{ $article->user->id }}">
        <img src="{{ $article->user->avatar }}" alt="">
      </a> 
      <div class="info_meta">
        <a class="nickname" target="_blank" href="/user/{{ $article->user->id }}">{{ $article->user->name }}</a>
        <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
        <span class="time" data-shared-at="2017-11-06T09:20:28+08:00">{{ $article->created_at }}发表了文章</span>
      </div>
    </div>
    <a class="headline paper_title" target="_blank" href="/article/{{ $article->id }}"><span>{{ $article->title }}</span></a>
    <p class="abstract">
      {{ $article->description }}
    </p>
    <div class="meta">
      <a target="_blank" href="/article/{{ $article->id }}" class="count count_link">
        <i class="iconfont icon-liulan"></i> 4184
      </a>        
      <a target="_blank" href="/article/{{ $article->id }}" class="count count_link">
        <i class="iconfont icon-svg37"></i> 70
      </a>      
      <span class="count"><i class="iconfont icon-03xihuan"></i> 288</span>
    </div>
  </div>
</li>
@endforeach
@endif
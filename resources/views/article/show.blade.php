@extends('layouts.app')

@section('title') {{ $article->title }} -{{ config('app.name') }}  @endsection
@section('keywords') {{ $article->keywords }} @endsection
@section('description') {{ $article->description() }} @endsection

@push('seo_metatags')
<meta property="og:type" content="article" />
<meta property="og:url" content="https://{{ get_domain() }}/article/{{ $article->id }}" />
<meta property="og:title" content="{{ $article->title }}" />
<meta property="og:description" content="{{ $article->description() }}" />

<meta property="og:image" content="{{ $article->primaryImage() }}" />
<meta name="weibo: article:create_at" content="{{ $article->created_at }}" />
<meta name="weibo: article:update_at" content="{{ $article->updated_at }}" />
@endpush

@section('content')

{{-- 允许cssfix来修复特定爬虫分类下的文章样式 --}}
@foreach($article->categories as $category)
     {!! link_source_css($category) !!}
@endforeach

<div id="detail">
    <section class="clearfix col-sm-offset-2 col-sm-8">
        <article>
          @editor
          <div class="text-right">
            @weixin
              <a href="/article/{{ $article->id }}" class="btn btn-danger">退出微信含微信二维码模式</a>
            @else
              <a href="?weixin=1" class="btn btn-success">微信粘贴模式</a>
            @endweixin
          </div>
          @endeditor

          <h1>{{ $article->title }}</h1>
          {{-- 作者 --}}
          @include('article.parts.author')
          {{-- 内容 --}}
          <div class="show-content">
            <p>@include('article.parts.body')</p>
          </div>
          {{-- 底部注释 --}}
          @include('article.parts.foot')
          {{-- 底部作者信息 --}}
          @include('article.parts.follow_card')
          
          {{-- 支持作者  --}}
          <div class="support-author">
            <p>{{ $article->user->tip_words ? $article->user->tip_words : '如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！' }}</p>

            @if(!$article->isSelf())
              @if($article->user->enable_tips)
                <a class="btn-base btn-theme" data-target=".modal-admire" data-toggle="modal">赞赏支持</a>
                <modal-admire article-id="{{ $article->id }}"></modal-admire>
              @endif
            @endif
            
            {{-- 赞赏用户 --}}
            @include('article.parts.supporters')
          </div>

          {{-- 喜欢和分享 --}}
          <div class="mate-bottom">
            <like 
              id="{{ $article->id }}" 
              type="article" 
              is-login="{{ Auth::check() }}"></like>
            
            <div class="share-circle">
              <a data-action="weixin-share" data-toggle="tooltip" data-toggle="tooltip" data-placement="top" title="分享到微信"><i class="iconfont icon-weixin1 weixin"></i></a>
              <a data-toggle="tooltip" data-placement="top" title="分享到微博"><i class="iconfont icon-sina weibo"></i></a>
              <a data-toggle="tooltip" data-placement="top" title="下载微博长图片"><i class="iconfont icon-zhaopian other"></i></a>
            </div>
          </div>
          {{-- 评论中心 --}}
          <comments type="articles" id="{{ $article->id }}" author-id="{{ $article->user_id }}"></comments>
        </article>
    </section>
</div>

@endsection

@push('section')
  {{-- 底部内容 --}}
  <div class="note-bottom">
    <div class="container">
      <div class="wrapper clearfix">
        <div class="col-sm-offset-2 col-sm-8">
          <div class="bottom-title"><span>被以下专题收入，发现更多相似内容</span></div>

           <div class="recommend-category">
            <a data-target=".modal-category-contribute" data-toggle="modal" class="category-label">
              <span class="name">＋ 收入我的专题</span>
            </a>
            @foreach($article->categories as $category)
            <a href="/{{ $category->name_en }}" class="category-label" title="{{ $category->id }}:{{ $category->name }}">
              <img src="{{ $category->smallLogo() }}" alt="{{ $category->id }}:{{ $category->name }}">
              <span class="name">{{ $category->name }}</span>
            </a>
            @endforeach
          </div>

          <div class="bottom-title">
            <span>推荐阅读</span>
            <a target="_blank" href="javascript:;" class="right">
             更多精彩内容<i class="iconfont icon-youbian"></i>
           </a>
          </div>

          <ul class="article-list">
            {{-- 文章 --}}
            @each('parts.article_item', $data['recommended'], 'article')
          </ul>   

        </div>
      </div>
    </div>
  </div>
@endpush

@push('side_tools')
  <article-tool 
    id="{{ $article->id }}" 
    is-self="{{ $article->isSelf() }}" 
    is-login="{{ Auth::check() }}">
    <share 
      placement='left' 
      url="{{ url('/article/'.$article->id) }}"
      article_id="{{ $article->id }}"
      author="{{ $article->user->name }}" 
      title="{{ $article->title }}"></share>
  </article-tool>
@endpush

@push('modals')
  @if(Auth::check())
  <modal-add-category article-id="{{ $article->id }}"></modal-add-category>
  <modal-category-contribute article-id="{{ $article->id }}"></modal-category-contribute>
  @endif
  {{-- 分享到微信 --}}
  <modal-share-wx></modal-share-wx>
@endpush
@extends('layouts.app')

@section('title')
  {{ $article->title }}
@endsection
@section('keywords')
  {{ str_replace(' ', ',', trim($article->keywords)) }}, {{ $article->category->name }}
@endsection
@section('description')
  {{ $article->description }}
@endsection

@section('content')
<div class="container">
  @if(!is_in_app())
  <ol class="breadcrumb">
    <li><a href="/">{{ config('app.name') }}</a></li>
    @if(!empty($data['parent_category']))
      <li><a href="/{{ $data['parent_category']->name_en }}">{{ $data['parent_category']->name }}</a></li>
    @endif
    <li><a href="/{{ $article->category->name_en }}">{{ $article->category->name }}</a></li>
    <li class="active">{{ $article->title }}</li>
  </ol>
  @endif

  <div class="content">
    <div class="panel panel-default">
      <div class="panel-heading">

        @include('article.parts.show_edit_buttons')

        <h1>{{ $article->title }}</h1>
        <p class="pull-right" title="移动端：{{ $article->hits_mobile }}, 手机端：{{ $article->hits_phone }}, 微信: {{ $article->hits_wechat }}, 爬虫：{{ $article->hits_robot }}">阅读次数: {{ $article->hits }}</p>
        <p class="small">
          作者: <a href="/user/{{ $article->user_id }}">{{ $article->author }}</a>  发布时间：{{ diffForHumansCN($article->created_at) }}
        </p>
        <p>
          @if($article->categories()->count())
             分类:
            @foreach($article->categories as $category_name)
               <a href="/{{ $category_name->name_en }}">{{ $category_name->name }}</a>

            @endforeach
          @else
             分类: <a href="/{{ $article->category->name_en }}">{{ $article->category->name }}</a>
          @endif
        </br>
          关键词:
          @foreach($article->tags as $tag)
          <a href="/tag/{{ $tag->name }}">{{  $tag->name  }}</a>
          @endforeach
        </p>

      </div>
      <div class="panel-body">
        <p class="lead">
          简介: {{ $article->description }}
        </p>
        <p>
          {!! $article->body !!}
        </p>
         @if($article->data_wz)
            @include('article.parts.article_json_wz')
         @endif
         @if($article->data_lol)
            @include('article.parts.article_json_lol')
         @endif
        <p>
          @if($article->edited_at)
          本文最后由 <a href="/user/{{ $article->user_id }}">{{ $article->user_name }}</a> 编辑 ({{ diffForHumansCN($article->edited_at) }})
          @endif
        </p>

        @if(!empty(Request::get('weixin')))
        <div class="alert alert-success">
          <strong>亲爱的微信用户，您好!</strong>
            <p>
              我们的内容您感兴趣吗？微信里长按识别一下,关注我们吧
            </p>
            <img src="/qrcode/{{ get_site_domain() }}.jpg" alt="" class="img img-responsive">
        </div>
        <div class="alert alert-info">
          <strong>想看小编"{{ $article->author }}"的更多文章吗?</strong>
            <p>
              点左下角的 <strong style="color:red">"阅读全文"</strong>, 可以和本站更多小编,网友互动...
            </p>
            <p>
               ￬ ￬ ￬
            </p>
        </div>
        @endif

        @if(Auth::check())
        <div class="row top10">
        <div class="col-md-12">
          <favorite id="{{ $article->id }}" type="article"></favorite>
          <like id="{{ $article->id }}" type="article"></like>
        </div>

        <div class="col-md-6 top10">
            <comment id="{{ $article->id }}" type="article" username="{{ Auth::user()->name }}"></comment>
        </div>
        </div>
        @endif

      </div>
    </div>
  </div>

  @include('article.parts.connections')

  @if(empty(Request::get('weixin')))
  <div class="row">
    <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">作者</h3>
        </div>
        <div class="panel-body">
           @include('user.parts.user_item', ['user' => $article->user])
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">感兴趣吗？微信扫一下关注吧</h3>
        </div>
        <div class="panel-body">
           <img src="/qrcode/{{ env('APP_DOMAIN') }}.jpg" alt="" class="img img-responsive">
           <p>
             @if(Agent::match('micromessenger')) 微信里阅读的朋友,您可以长按二维码,然后识别就可以关注了 @endif
           </p>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">同类文章</h3>
        </div>
        <div class="panel-body">
          @foreach($data['related'] as $article)
            @include('article.parts.article_item')
          @endforeach
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection

@push('scripts')
    @include('parts.js_for_app')
@endpush
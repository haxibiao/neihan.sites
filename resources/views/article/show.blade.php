@extends('layouts.app')

@section('title')
  {{ $article->title }}
@endsection
@section('keywords')
  {{ str_replace(' ', ',', $article->keywords) }}
@endsection
@section('description')
  {{ $article->description }}
@endsection

@section('content')
<div class="container">
  <ol class="breadcrumb">
    <li><a href="/">{{ config('app.name') }}</a></li>
    <li><a href="/{{ $article->category->name_en }}">{{ $article->category->name }}</a></li>
    <li class="active">{{ $article->title }}</li>
  </ol>

  <div class="content">
    <div class="panel panel-default">
      <div class="panel-heading">
        @if(Auth::check() && (Auth::user()->id == $article->user_id))
        <p class="pull-right">
            <a href="/article/{{ $article->id }}/edit" class="btn btn-danger">编辑文章</a>
        </p>
        @endif
        <h1>{{ $article->title }}</h1>
        <p class="pull-right" title="移动端：{{ $article->hits_mobile }}, 手机端：{{ $article->hits_phone }}, 微信: {{ $article->hits_wechat }}, 爬虫：{{ $article->hits_robot }}">阅读次数: {{ $article->hits }}</p>
        <p>
          作者: <a href="/user/{{ $article->user_id }}">{{ $article->author }}</a>  发布时间：{{ diffForHumansCN($article->created_at) }}
        </p>
        <p>
          分类: <a href="/{{ $article->category->name_en }}">{{ $article->category->name }}</a> 
       
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
        <p>
          @if($article->edited_at || $article->user_name != $article->author)
          本文最后由 <a href="/user/{{ $article->user_id }}">{{ $article->user_name }}</a> 编辑 ({{ diffForHumansCN($article->edited_at) }})
          @endif
        </p>
      </div>
    </div>
  </div>

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
    </div>
    <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">感兴趣吗？扫一下关注吧</h3>
        </div>
        <div class="panel-body">
           <img src="/qrcode/{{ env('APP_DOMAIN') }}.jpg" alt="" class="img img-responsive">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">相关图片</h3>
        </div>
        <div class="panel-body">
          @foreach($article->images as $image) 
            <img src="{{ $image->path_small }}" alt="" class="img img-responsive col-xs-6 col-sm-4 col-md-3">
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
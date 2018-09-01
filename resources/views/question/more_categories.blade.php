@extends('layouts.app')
@section('title')
  热门问答分类 - {{ env('APP_NAME') }}
@stop
@section('content')
<div class="recommend-content">
    <div class="page-banner">
      {{-- <img src="/images/recommend_banner.png" alt=""> --}}
      <div class="banner-img recommend-question">
        <div>
          <i class="iconfont icon-help"></i>
          <span>热门问答分类</span>
        </div>
      </div>
    </div>
    <div class="recommend-list">
     <ul class="clearfix">
      @foreach($categories->chunk(3) as $category_group)
        <div class="row">
           @each('question.parts.category_card', $categories, 'category')
        </div>
      @endforeach
     </ul> 
     <p>{!! $categories->links() !!}</p>
     {{-- <a class="btn-base btn-more" href="javascript:;">加载更多</a> --}}
    </div>
  </div>
@endsection
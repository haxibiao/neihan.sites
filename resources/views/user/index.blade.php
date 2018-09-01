@extends('layouts.app')

@section('title') 全部用户 @endsection

@section('content')
<div id="recommendations">
    <div class="page-banner">
      {{-- <img src="/images/recommend-author.png" alt=""> --}}
      <div class="banner-img recommend-author">
        <div>
          <i class="iconfont icon-admin"></i>
          <span>推荐作者</span>
        </div>
      </div>
      <a target="_blank" class="help" href="/article/1375">
          <i class="iconfont icon-bangzhu"></i>
          如何成为签约作者
      </a>
    </div>
    <div class="recommend-list">
     <ul class="clearfix">
      @foreach($users->chunk(3) as $user_group)
        <div class="row">
           @each('user.parts.author_card', $user_group, 'user')
        </div>
      @endforeach
     </ul>
     {!! $users->links() !!}
     {{-- <a class="btn-base btn-more" href="javascript:;">加载更多</a>  --}}
    </div>
  </div>
@endsection
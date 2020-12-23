@extends('layouts.app')

@section('title')
	视频主页 - {{ seo_site_name() }}
@stop
@section('keywords') 视频主页,{{ get_seo_keywords() }} @endsection
@section('description') 视频主页, {{ get_seo_description() }} @endsection

@section('content')
<div class="container">
    @include('video.parts.top_collections')
    <div class="vd-head">
          <h3 class="vd-title">
            <span class="title-icon">
              <i class="iconfont icon-huo"></i>精彩文章-推荐分类
            </span>
          </h3>
    </div>
     @each('video.parts.hot_category_video', $data,"articles")
     <video-list api="api/getlatestVideo" is-desktop="{{ isDeskTop() == 1 }}" ></video-list>
</div>
@stop

@push('scripts')
  <script>
   	$(".cateory-logo").on('error', function(){
    	$(this).attr("src", "/images/default_logo.png");
    });
  </script>
@endpush

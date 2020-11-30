@extends('layouts.app')

@section('title')
	视频主页 - {{ seo_site_name() }}
@stop
@section('keywords') 视频主页,{{ get_seo_keywords() }} @endsection
@section('description') 视频主页, {{ get_seo_description() }} @endsection



@section('content')
<div class="container">
     @include('video.parts.top_list')
     @each('video.parts.hot_category_video', $data,"posts")
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

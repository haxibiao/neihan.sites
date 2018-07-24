@extends('layouts.app')

@section('title')
	视频主页 - {{ config('app.name') }}
@stop
@section('keywords') 视频主页,{{ implode(",", array_keys($data)) }} @endsection
@section('description') 视频主页, {{ implode(",", array_keys($data)) }} @endsection

@section('content')
<div class="container">
     @include('video.parts.top_list')
     @each('video.parts.hot_category_video', $data, "articles")
     <video-list api="api/getlatestVideo"></video-list>
</div>
@stop

@push('scripts')
  <script>
   	$(".cateory-logo").on('error', function(){
    	$(this).attr("src", "/images/default_logo.png");
    });
  </script>
@endpush
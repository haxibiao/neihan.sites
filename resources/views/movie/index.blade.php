@extends('layouts.app')

@section('title')
	电影主页 - {{ seo_site_name() }}
@stop
@section('keywords') 电影主页,{{ implode(",", array_keys($data)) }} @endsection
@section('description') 电影主页, {{ implode(",", array_keys($data)) }} @endsection

@section('content')
<div class="container">
	 {{-- 置顶电影 --}}
	 @include('movie.parts.top_list')
	 {{-- 热门分类 --}}
	 @foreach($data as $category => $movies)
		@include('movie.parts.hot_categories', ['category'=>$category, 'movies'=>$movies])
	 @endforeach
	 
	 {{-- 无限刷新 --}}
     {{-- <video-list api="api/getlatestVideo" is-desktop="{{ isDeskTop() == 1 }}" ></video-list> --}}
</div>
@stop

@push('scripts')
  <script>
   	$(".cateory-logo").on('error', function(){
    	$(this).attr("src", "/images/default_logo.png");
    });
  </script>
@endpush
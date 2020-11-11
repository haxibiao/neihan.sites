
<nav class="navbar navbar-default  navbar-fixed-top" role="navigation">
		<div class="width-limit">
			@section('logo')
				@if( isMobile() )
					<a class="logo" href="/">
						<img src="{{ seo_small_logo() }}" alt="">
					</a>
				@else
				   <a class="logo" href="/">
						<img src="{{ seo_small_logo() }}" alt="">
					</a>
				@endif
			@show

{{-- 			
			@if(starts_with(request()->path(), 'question')) 
				<div class="ask"><a href="/login" data-toggle="modal" class="btn-base btn-theme"><span class="iconfont icon-maobi hidden-xs"></span>提问</a></div>
			@else 
				<div class="creation hidden-xs"><a href="/write" class="btn-base btn-theme"><span class="iconfont icon-maobi"></span>发布</a></div>
			@endif	 --}}
				@if (!isRecording())
					<div class="register"><a href="/register" class="btn-base theme-tag">注册</a></div>
				@endif

			<!-- <a target="_blank" href="{{ request()->path() == "app" ? env('APK_URL') : "/app" }}"  class="download-app"> 
					<p>下载APP</p>
			</a>-->
			<a  href="{{  request()->path() == "app" ? "#tancen" : "/app" }}"  class="download-app"> 
					<p>下载APP</p>
			</a>

				@if (!isRecording())
					<a href="/login" class="login btn">登录</a>
					@endif

{{-- 			<a class="search"> 
				<search-box is-desktop="{{ isDeskTop() == 1 }}"></search-box>
			</a> --}}
			<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse navbar-ex1-collapse">
						<ul class="nav navbar-nav">
							@if (!isRecording())
							<li class="tab {{ get_active_css('/') }}"><a href="/"><i class="iconfont icon-faxian hidden-xs hidden-md"></i><span class="hidden-sm">首页</span></a></li>
							@endif
								@if (!isRecording())
							<li class="tab {{ get_active_css('video') }}"><a target="{{ isDeskTop()? '_blank':'_self' }}" href="/video"><i class="iconfont icon-shipin3 hidden-xs hidden-md"></i><span class="hidden-sm">视频</span></a></li>
								@endif
								@if (!isRecording())
							<li class="tab {{ get_active_css('app') }}"><a target="{{ isDeskTop()? '_blank':'_self' }}" href="/app"><i class="iconfont icon-ordinarymobile hidden-xs hidden-md"></i><span class="hidden-sm">下载App</span></a></li>
								@endif
						</ul>
						@if (!isRecording())
						<a class="search"> 
							<search-box is-desktop="{{ isDeskTop() == 1 }}"></search-box>
						</a>
						@endif
					</div>
			</div>
		</div>
	</nav>

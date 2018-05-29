	<nav class="navbar navbar-default  navbar-fixed-top" role="navigation">
		<div class="width-limit">
			<a class="logo" href="/">
				<img src="https://www.ainicheng.com/logo/ainicheng.com.small.png" alt="">
				{{-- <img src="/logo/{{ get_domain() }}.small.png" alt=""> --}}
			</a>
			@if(starts_with(request()->path(), 'question'))
				<div class="ask"><a href="/login" data-toggle="modal" class="btn-base btn-theme"><span class="iconfont icon-maobi hidden-xs"></span>提问</a></div>
			@else 
				<div class="creation hidden-xs"><a href="/write" class="btn-base btn-theme"><span class="iconfont icon-maobi"></span>创作</a></div>
			@endif						
			<div class="register"><a href="/register" class="btn-base theme-tag">注册</a></div>
			<a href="/login" class="login btn">登录</a>
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
							<li class="tab {{ get_active_css('/') }}"><a href="/"><i class="iconfont icon-faxian hidden-xs hidden-md"></i><span class="hidden-sm">首页</span></a></li>
							<li class="tab {{ get_active_css('question') }}"><a href="/question"><i class="iconfont icon-help hidden-xs hidden-md"></i><span class="hidden-sm">问答</span></a></li>
							<li class="tab {{ get_active_css('download-app') }}"><a href="/apps"><i class="iconfont icon-ordinarymobile hidden-xs hidden-md"></i><span class="hidden-sm">下载App</span></a></li>
						</ul>
						<search-box></search-box>
					</div>
			</div>
		</div>
	</nav>

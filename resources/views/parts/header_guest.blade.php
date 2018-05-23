<div id="header">
	<nav class="navbar navbar-default" role="navigation">
		<div class="container">
			<div class="navbar-wrp">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">
						<img src="/logo/{{ get_domain() }}.small.png" alt="">
					</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse navbar-ex1-collapse">
					<ul class="nav navbar-nav">
						<li class="{{ get_active_css('/') }}"><a href="/"><i class="iconfont icon-faxian hidden-xs hidden-md"></i><span class="hidden-sm">首页</span></a></li>
						<li class="{{ get_active_css('question') }}"><a href="/question"><i class="iconfont icon-help hidden-xs hidden-md"></i><span class="hidden-sm">问答</span></a></li>
						<li class="{{ get_active_css('download-app') }}"><a href="/apps"><i class="iconfont icon-ordinarymobile hidden-xs hidden-md"></i><span class="hidden-sm">下载App</span></a></li>
					</ul>
					
					<search-box></search-box>
					
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/login">登录</a></li>
						<li class="register"><a href="/register" class="btn-base theme-tag">注册</a></li>
						@if(starts_with(request()->path(), 'question'))
							<li class="creation"><a href="/login" data-toggle="modal" class="btn-base btn-theme"><span class="iconfont icon-maobi"></span>提问</a></li>
						@else 
							<li class="creation"><a href="/write" class="btn-base btn-theme"><span class="iconfont icon-maobi"></span>创作</a></li>
						@endif						
					</ul>
				</div><!-- /.navbar-collapse -->
			</div>
		</div>
	</nav>
</div>
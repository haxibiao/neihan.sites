@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="jumbotron">
			<div class="container">
				<h1>欢迎进入管理中心</h1>
				<p>您可以有这些管理操作:</p>
				<p>
					<div class="panel panel-default">
					  <div class="panel-heading">用户设置</div>
					  <div class="panel-body">
					    <a class="btn btn-default" href="/admin/users">管理用户</a>
					  </div>
					</div>
					<div class="panel panel-default">
					  <div class="panel-heading">内容设置</div>
					  <div class="panel-body">
					    <a class="btn btn-default" href="/admin/article-push">推送文章</a>
					    <a class="btn btn-default" href="/admin/stick-articles">置顶文章</a>
					    <a class="btn btn-default" href="/admin/stick-video-categorys">置顶视频专题</a>
						<a class="btn btn-default" href="/admin/stick-videos">置顶视频</a>
					    <a class="btn btn-default" href="/admin/articles">作品批量管理</a>
					    <a class="btn btn-default" href="/admin/stick-categorys">置顶专题</a>
					  </div>
					</div>
					<div class="panel panel-default">
					  <div class="panel-heading">系统设置</div>
					  <div class="panel-body">
					    <a class="btn btn-default" href="/admin/seo-config">SEO配置</a>
						<a class="btn btn-default" href="/admin/friend-links">友情链接</a>
						<a class="btn btn-default" href="/admin/app-download-config">下载页信息设置</a>
					  </div>
					</div>
					
					
					
					
					
				</p>
			</div>
		</div>
	</div>
@stop
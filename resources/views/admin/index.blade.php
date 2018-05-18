@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="jumbotron">
			<div class="container">
				<h1>欢迎进入管理中心</h1>
				<p>您可以有这些管理操作:</p>
				<p>
					<a class="btn btn-default" href="/admin/users">管理用户</a>
					<a class="btn btn-default" href="/admin/seo-config">SEO配置</a>
					<a class="btn btn-default" href="/admin/article-push">推送文章</a>
					<a class="btn btn-default" href="/admin/friend-links">友情链接</a>
					<a class="btn btn-default" href="/admin/stick-articles">置顶文章</a>
					<a class="btn btn-default" href="/admin/stick-categorys">置顶专题</a>
				</p>
			</div>
		</div>
	</div>
@stop
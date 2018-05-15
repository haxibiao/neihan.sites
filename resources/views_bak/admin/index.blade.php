@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="jumbotron">
			<div class="container">
				<h1>欢迎进入管理中心</h1>
				<p>您可以有这些管理操作:</p>
				<p>
					<a class="btn btn-primary btn-lg" href="/admin/users">管理用户</a>
					{{-- <a class="btn btn-primary btn-lg" href="/admin/users">管理用户</a>
					<a class="btn btn-primary btn-lg" href="/admin/users">管理用户</a> --}}
				</p>
			</div>
		</div>
	</div>
@stop
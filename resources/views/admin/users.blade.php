@extends('layouts.app')

@section('content')
	<div class="container">
		<ol class="breadcrumb">
			<li>
				<a href="/admin">后台管理</a>
			</li>
			<li class="active">用户管理</li>
		</ol>
		<div class="panel panel-default">
			<div class="panel-heading">
				{!! Form::open(['method' => 'GET', 'route' => 'admin.users_search', 'class' => 'form-horizontal']) !!}
				<div class="row">
					<div class="col-sm-10 col-md-4">
					<input type="search" name="name_email" id="input" class="form-control" value="" placeholder="Email或用户名">
					<div class="checkbox">
						只搜
						<label>
							<input type="checkbox" value="1" name="is_admin">
							管理员
						</label>
						<label>
							<input type="checkbox" value="1" name="is_editor">
							小编
						</label>
						<label>
							<input type="checkbox" value="1" name="is_signed">
							签约作者
						</label>
					</div>
					</div>
					<div class="col-sm-2">
						<button type="submit" class="btn btn-primary">搜索</button>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
			<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>昵称</th>
						<th>身份</th>
						<th>加入时间</th>
						<th>介绍</th>
						<th style="width:200px"></th>
					</tr>
				</thead>
				<tbody>
				@foreach ($users as $user)
					<tr>
						<td><a href="/user/{{ $user->id }}">{{ $user->id }}</a></td>
						<td>{{ $user->name }}</td>
						<td>
						@if($user->is_signed)
					          <img class="badge-icon" style="width:20px" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
					        @endif
					        @if($user->is_editor)
					          <img class="badge-icon" style="width:20px" src="/images/editor.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}小编" alt="">
					        @endif
					    </td>
						<td>{{ $user->createdAt() }}</td>
						<td>{{ $user->introduction }}</td>
						<td>
							<a href="/login-as/{{ $user->id }}" class="btn btn-link">登录</a>
							<a class="btn-base pull-right" href="/user/{{ $user->id }}/edit" role="button" target="_blank">编辑</a>
							{!! Form::open(['method' => 'delete', 'route' => ['user.destroy', $user->id], 'class' => 'form-horizontal pull-right']) !!}
						        {!! Form::submit("删除", ['class' => 'btn-base btn-sm']) !!}							    
							{!! Form::close() !!}
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<p>
				{!! $users->links() !!}
			</p>
			</div>
		</div>
	</div>
@stop
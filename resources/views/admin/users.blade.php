@extends('layouts.app')

@section('content')
	<div class="container">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">用户管理</h3>
			</div>
			<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>昵称</th>
						<th> 是否编辑 </th>
						<th>状态</th>
						<th>介绍</th>
						<th style="width:200px"></th>
					</tr>
				</thead>
				<tbody>
				@foreach ($users as $user)
					<tr>
						<td><a href="/user/{{ $user->id }}">{{ $user->id }}</a></td>
						<td>{{ $user->name }}</td>
						<td>{{ $user->is_editor ? '是' : '' }}</td>
						<td>{{ $user->status == -1 ? '已删除' : '正常' }}</td>
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
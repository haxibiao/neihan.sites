@extends('layouts.app')

@section('content')
	<div class="container">
		<ol class="breadcrumb">
			<li>
				<a href="/admin">管理中心</a>
			</li>
			<li class="active">用户管理</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">用户</h3>
			</div>
			<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>name</th>
						<th>编辑</th>
						<th>Seoer</th>
						<th>status</th>
						<th>introduction</th>
						<th style="width:200px"></th>
					</tr>
				</thead>
				<tbody>
				@foreach ($users as $user)
					<tr>
						<td>{{ $user->id }}<a href="/login-as/{{ $user->id }}" class="btn btn-link">登录</a>
						</td>
						<td>{{ $user->name }}</td>
						<td>{{ $user->is_editor ? '是' : '' }}</td>
						<td>{{ $user->is_seoer ? '是' : '' }}</td>
						<td>{{ $user->status == -1 ? '已删除' : '正常' }}</td>
						<td>{{ $user->introduction }}</td>
						<td>
							<a class="btn btn-primary pull-right left10" href="/user/{{ $user->id }}/edit" role="button" target="_blank">编辑</a>
							{!! Form::open(['method' => 'delete', 'route' => ['user.destroy', $user->id], 'class' => 'form-horizontal']) !!}
							    <div class="btn-group pull-right">
							        {!! Form::submit("删除", ['class' => 'btn btn-danger']) !!}
							    </div>
							{!! Form::close() !!}
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			</div>
		</div>
	</div>
@stop
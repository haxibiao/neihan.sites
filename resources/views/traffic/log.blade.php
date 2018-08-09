@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Log</h3>
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-responsive">
					<tr>
						<th>文章</th>
						<th>时间</th>
						<th>设备</th>
						<th>系统</th>
						<th>浏览器</th>
						<th>爬虫</th>
						<th>微信</th>
						<th>手机</th>
						<th>移动端</th>
						<th>分类</th>
					</tr>
					@foreach($traffics as $traffic)
					<tr>
						<td>
							@if($traffic->article_id)
							<a href="/article/{{ $traffic->article_id }}">{{ str_limit($traffic->article->title, 10) }}</a>
							@endif
						</td>
						<td>
							{{ $traffic->created_at->diffForHumans() }}
						</td>
						<td>
							{{ $traffic->device }}
						</td>
						<td>
							{{ $traffic->platform }}
						</td>
						<td>
							{{ $traffic->browser }}
						</td>
						<td>
							{{ $traffic->robot }}
						</td>
						<td>
							{{ $traffic->is_wechat }}
						</td>
						<td>
							{{ $traffic->is_phone }}
						</td>
						<td>
							{{ $traffic->is_mobile }}
						</td>
						<td>
							{{ $traffic->category }}
						</td>
					</tr>
					@endforeach
				</table>

				<p>
					{{ $traffics->render() }}
				</p>
			</div>
		</div>
	</div>
@stop
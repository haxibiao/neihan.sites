@extends('layouts.app')

@section('content')
	<div class="container">

		<div class="col-md-6">
			<bar title-one="最近7天流量" chart-labels='{{ json_encode(array_keys($datas['traffic_by_date'])) }}' chart-data='{{ json_encode(array_values($datas['traffic_by_date'])) }}' />
		</div>
		<div class="col-md-6">
			<ul class="nav nav-tabs">
			  <li role="presentation" class="{{ get_active_css('traffic', 1) }}"><a href="/traffic">今日</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-1') }}"><a href="/traffic/days-1">周{{ \Carbon\Carbon::now()->subDay(1)->dayOfWeek }}</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-2') }}"><a href="/traffic/days-2">周{{ \Carbon\Carbon::now()->subDay(2)->dayOfWeek }}</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-3') }}"><a href="/traffic/days-3">周{{ \Carbon\Carbon::now()->subDay(3)->dayOfWeek }}</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-4') }}"><a href="/traffic/days-4">周{{ \Carbon\Carbon::now()->subDay(4)->dayOfWeek }}</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-5') }}"><a href="/traffic/days-5">周{{ \Carbon\Carbon::now()->subDay(5)->dayOfWeek }}</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-6') }}"><a href="/traffic/days-6">周{{ \Carbon\Carbon::now()->subDay(6)->dayOfWeek }}</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-7') }}"><a href="/traffic/days-7">周{{ \Carbon\Carbon::now()->subDay(7)->dayOfWeek }}</a></li>
			</ul>
		</div>
	
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">访问统计 (总计:{{ $all }})</h3>
				</div>
				<div class="panel-body">
					<ul class="list-group">
						@foreach($counts as $name => $count)
						<li class="list-group-item">
						<div class="pull-right" style="width: 100px">
							<span class="badge">{{ $count }}</span>
							{{ $name }}
						</div>
							<div class="progress">
							  <div class="progress-bar" role="progressbar" aria-valuenow="{{ ceil( 100 * $count / $all ) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ ceil( 100 * $count / $all ) }}%;">
							    {{ ceil( 100 * $count / $all ) }}%
							  </div>
							</div>
						</li>
						@endforeach
					</ul>	
				</div>
				<div class="panel-footer">
					<a href="/traffic/log" class="btn btn-default">详细日志</a>
				</div>
			</div>	
		</div>	
	
		@foreach($data as $name => $counts)
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ $counts['cn_name'] }}</h3>
				</div>
				<div class="panel-body">
					<ul class="list-group">
						@foreach($counts['data'] as $key => $count)
							@if(!empty($key))
								<li class="list-group-item">
									<span class="badge">{{ $count }}</span>
									<a href="/traffic/{{ $name }}/{{ urlencode($key)}}">{{ $key }}</a>
								</li>
							@endif
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		@endforeach
	</div>
@stop
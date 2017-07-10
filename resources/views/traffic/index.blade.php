@extends('layouts.app')

@section('content')
	<div class="container">

		<div class="col-md-12 bottom10">
			<ul class="nav nav-tabs">
			  <li role="presentation" class="{{ get_active_css('traffic', 1) }}"><a href="/traffic">今日</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-1') }}"><a href="/traffic/days-1">昨日</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-2') }}"><a href="/traffic/days-2">2天前</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-3') }}"><a href="/traffic/days-3">3天前</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-4') }}"><a href="/traffic/days-4">4天前</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-5') }}"><a href="/traffic/days-5">5天前</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-6') }}"><a href="/traffic/days-6">6天前</a></li>
			  <li role="presentation" class="{{ get_active_css('traffic/days-7') }}"><a href="/traffic/days-7">7天前</a></li>
			</ul>
		</div>

	
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">访问统计</h3>
				</div>
				<div class="panel-body">
					<ul class="list-group">
						@foreach($counts as $name => $count)
						<li class="list-group-item">
							<span class="badge">{{ $count }}</span>
							{{ $name }} {{ ceil( 100 * $count / $all ) }}%
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
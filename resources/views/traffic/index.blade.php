@extends('layouts.app')

@section('content')
	<div class="container">
		<ul class="list-group">
			@foreach($data as $name => $count)
			<li class="list-group-item">
				<span class="badge">{{ $count }}</span>
				{{ $name }} {{ 100*$count/$all }}%
			</li>
			@endforeach
		</ul>

		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Log</h3>
			</div>
			<div class="panel-body">
				<table class="table-condensed">
					@foreach($traffics as $traffic)
					<tr>
						<td>
							{{ $traffic->id }}
						</td>
						<td>
							{{ $traffic->created_at->diffForHumans() }}
						</td>
						<td>
							{{ $traffic }}
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
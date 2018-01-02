@extends('layouts.app')

@section('title')

@stop

@section('content')
<div class="container">
     <div class="panel panel-default">
     	<div class="panel-heading">
     		<h3 class="panel-title">{{ $team->name }}</h3>
     	</div>
     	@php
     		$team_count=$team->compare->teams()->count();
     	@endphp
     	<div class="panel-body">
     		 已经创建的队伍数:{{ $team_count }}/赛季总队伍数:{{ $team->compare->count }}
     	</div>

     	@if($team_count >= $team->compare->count)
     	     <a href="/make-team-matches?compare_id={{ $team->compare_id }}">开启比赛</a>
     	@endif
     </div>
</div>
@stop

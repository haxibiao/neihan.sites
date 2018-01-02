@extends('layouts.app')

@section('title')
    赛季队伍情况详情
@stop

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                {{ $team->name }}
            </h3>
        </div>
        @php
               $team_count=$team->compare->teams()->count();
        @endphp
        <div class="panel-body">
            已经创建的队伍数:{{ $team_count }}/赛季总队伍数:{{ $team->compare->count }}
        </div>
        @if($team_count >= $team->compare->count)

        <div class="text-center" style="margin: 3%">
            <a class="btn btn-success" href="/make-team-matches?compare_id={{ $team->compare_id }}">
                开启比赛
            </a>
        </div>

        @else
        <div class="text-center" style="margin: 3%">
            参赛队伍还不足够!
            <a class="btn btn-success" href="/team/create">
                立即添加
            </a>
        </div>
        @endif
    </div>
</div>
@stop

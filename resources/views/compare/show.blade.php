@extends('layouts.app')

@section('title')
      赛季详情
@stop

@section('content')
@if(!empty($team))
<div class="container">
    <h2 class="text-center">
        {{ $compare->name }}
    </h2>
    参赛队伍:
        @foreach($teams as $team)
    <button class="btn btn-info" style="margin: 4%" type="button">
        {{ $team->name }}
    </button>
    @endforeach
    <br/>
    <br/>
    <br/>
    第一轮比赛:
    <br/>
    @foreach($matchs['1'] as $match)
    <button class="btn btn-info" style="margin: 4%" type="button">
        {{ $match->TA }}
    </button>
    对战
    <button class="btn btn-info" style="margin: 4%" type="button">
        {{ $match-> TB }}
    </button>
    @if($match->winner)
        该场已经结束:比分为{{ $match->score }}
    @else
       该场比赛还在进行
    <a class="btn btn-success" href="/match/{{ $match->id }}/edit" style="margin: 4%">
        添加统计结果
    </a>
    @endif
    <br/>
    @endforeach

    第二轮比赛(淘汰赛):
    <br/>
    @foreach($matchs['2'] as $match)
    <button class="btn btn-info" style="margin: 4%" type="button">
        {{ $match->TA }}
    </button>
    对战
    <button class="btn btn-info" style="margin: 4%" type="button">
        {{ $match-> TB }}
    </button>
    @if($match->winner)
        该场已经结束:比分为{{ $match->score }}
    @else
        该场比赛还在进行
    <a class="btn-success" href="/match/{{ $match->id }}/edit">
    </a>
    @endif
    <br/>
    @endforeach
</div>
@else
<div class="container">
    <div class="text-center">
        参赛队伍还不足够! <a class="btn btn-info" href="">查看队伍情况</a> 或者
        <a class="btn btn-success" href="">
            立即添加
        </a>
    </div>
</div>
@endif
@stop

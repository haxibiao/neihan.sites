@extends('layouts.app')

@section('title')
      赛季详情
@stop

@section('content')
<div class="container">
    
     <h2 class="text-center">{{ $compare->name }}</h2>

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
        {{ $match->	TB }}
    </button>
    @if($match->winner)
        该场已经结束:比分为{{ $match->score }}
    @else
       该场比赛还在进行   <a class="btn btn-success" href="/match/{{ $match->id }}/edit" style="margin: 4%">添加统计结果</a>
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
        {{ $match->	TB }}
    </button>
    @if($match->winner)
        该场已经结束:比分为{{ $match->score }}
    @else
        该场比赛还在进行  <a class="btn-success" href="/match/{{ $match->id }}/edit"></a>
    @endif
    <br/>
    @endforeach
</div>
@stop

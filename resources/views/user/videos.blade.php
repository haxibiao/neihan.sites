@extends('layouts.app')

@section('title')
    {{ $user->name }}的视频
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li><a href="/user/{{ $user->id }}">{{ $user->name }}</a></li>
      </ol>
    <div class="panel panel-default">
        <div class="panel-body">
            <img alt="" class="img img-circle" src="{{ $user->avatar() }}">
                <h4>
                    {{ $user->name }}
                </h4>
                <p>
                    {{ $user->email }}
                </p>
                <p>
                	{{ $user->introduction }}
                </p>
            </img>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
        <a class="pull-right" href="/user/{{ $user->id }}/videos">更多</a>
            <h3 class="panel-title" style="line-height: 30px">视频({{ $data['videos']->total() }})</h3>
        </div>
        <div class="panel-body">
            @foreach($data['videos'] as $video)
                @include('video.parts.video_item_can_edit')
            @endforeach   
            <p>
                {!! $data['videos']->render() !!}
            </p>         
        </div>
    </div>
</div>
@endsection

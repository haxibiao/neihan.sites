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
                @if(canEdit($video))
                <div class="pull-right">
                  {!! Form::open(['method' => 'delete', 'route' => ['video.destroy', $video->id], 'class' => 'form-horizontal pull-left']) !!}
                    {!! Form::submit('删除', ['class' => 'btn btn-default btn-small']) !!}                
                  {!! Form::close() !!}
                    <a class="btn btn-primary btn-small" href="/video/{{ $video->id }}/edit" role="button" style="margin-left: 5px">
                        编辑
                    </a>
                </div>
                @endif
                @include('video.parts.video_item')
            @endforeach   
            <p>
                {!! $data['videos']->render() !!}
            </p>         
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title')
    {{ $user->name }}的视频动态
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li><a href="/user/{{ $user->id }}">{{ $user->name }}</a></li>
      </ol>
    <div class="panel panel-default">
        <div class="panel-body">
            <img alt="" class="img img-circle avatar_small" src="{{ $user->avatar() }}">
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
            <h3 class="panel-title" style="line-height: 30px">视频动态({{ $data['videoPosts']->total() }})</h3>
        </div>
        <div class="panel-body">
            @foreach($data['videoPosts'] as $post)
                @include('video.parts.video_post')
            @endforeach   
            <p>
                {!! $data['videoPosts']->render() !!}
            </p>         
        </div>
    </div>
</div>
@endsection

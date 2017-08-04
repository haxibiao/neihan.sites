@extends('layouts.app')

@section('title')
    {{ $user->name }}的收藏
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li><a href="/user/{{ $user->id }}">{{ $user->name }}</a></li>
      </ol>
    <div class="panel panel-default">
        <div class="panel-body">
            <img alt="" class="img img-circle" src="{{ get_avatar($user) }}">
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

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">            
                    <h3 class="panel-title" style="line-height: 30px">收藏的文章({{ $data['fav_articles']->total() }})</h3>
                </div>
                <div class="panel-body">
                    @foreach($data['fav_articles'] as $fav)
                        <p class="pull-right small">
                            收藏于: {{ diffForHumansCN($fav->created_at) }}
                        </p>
                        @include('article.parts.article_item', ['article' => $fav->article])
                    @endforeach   
                    <p>
                        {!! $data['fav_articles']->render() !!}
                    </p>         
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">            
                    <h3 class="panel-title" style="line-height: 30px">收藏的视频({{ $data['fav_videos']->total() }})</h3>
                </div>
                <div class="panel-body">
                    @foreach($data['fav_videos'] as $fav)
                        <p class="pull-right small">
                            收藏于: {{ diffForHumansCN($fav->created_at) }}
                        </p>
                        @include('video.parts.video_item', ['video' => $fav->video, 'is_side' => 1])
                    @endforeach   
                    <p>
                        {!! $data['fav_videos']->render() !!}
                    </p>         
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

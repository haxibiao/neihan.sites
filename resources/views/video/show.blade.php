@extends('layouts.app')

@section('title')
	视频: {{ $video->title }} - 
@stop

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">            
            @if(checkEditor())
            <div class="pull-right">
                <a class="btn btn-success" href="/video/{{ $video->id }}/edit" role="button">
                    编辑
                </a>
            </div>
            @endif
            <h3 class="panel-title" style="line-height: 30px">
                {{  $video->title }}
            </h3>
        </div>
        <div class="panel-body">
            <p>
                上传用户: <a href="/user/{{ $video->user->id }}">{{ $video->user->name }}</a>
            </p>
            <p>
                最后修改: {{ $video->updatedAt() }}
            </p>
            @if(!empty($video->introduction))
            <p class="lead">
                视频简介: {{ $video->introduction }}
            </p>
            @endif
            <div class="embed-responsive embed-responsive-16by9">
                <video controls="" poster="{{ $video->cover }}" preload="auto">
                    <source src="{{ $video->path }}" type="video/mp4">
                    </source>
                </video>
            </div>

            @if(Auth::check())
            <div class="row top10">
                <div class="col-md-12">
                    <favorite id="{{ $video->id }}" type="video"></favorite>
                    <like id="{{ $video->id }}" type="video"></like>
                </div>

                <div class="col-md-6 top10"> 
                    <comment id="{{ $video->id }}" type="video" username="{{ Auth::user()->name }}"></comment>
                </div>
            </div>
            @endif

            <div class="top10">
                @include('article.parts.connections')
            </div>

            @if(!$video->articles->isEmpty())
            <div class="top10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">相关文章</h3>
                    </div>
                    <div class="panel-body">
                        @foreach($video->articles as $article) 
                            @include('article.parts.article_item')
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@stop

@push('scripts')
    @include('parts.js_for_app')
@endpush
@extends('layouts.app')

@section('title')
	视频: {{ $video->title }} - 
@stop

@section('content')
<div class="container">
    
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        @if($video->category)
        <li><a href="/$video->category->name_en">{{ $video->category->name}}</a></li>
        @endif
        <li class="active">{{ $video->title }}</li>
      </ol>

    <div class="col-md-8">
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
                    <video controls="" poster="{{ $video->cover }}" preload="auto" autoplay="true">
                        <source src="{{ $video->source() }}" type="video/mp4">
                        </source>
                    </video>
                </div>

                @if(Auth::check())
                <div class="row top10">
                    <div>
                       <like id="{{ $video->id }}" type="videos" is-login="{{ Auth::check() }}"></like>
                    </div>

                    <div class="top10"> 
                        {{-- 评论中心 --}}
                        <comments type="videos" id="{{ $video->id }}" author-id="{{ $video->user_id }}"></comments>
                    </div>
                </div>
                @endif

                
                {{-- TODO: 暂时隐藏  .... 编辑推荐关联去看的文章  --}}
               {{--  <div class="top10">
                    @include('article.parts.connections')
                </div> --}}

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

    <div class="col-md-4">
        @foreach($data['related'] as $video)
        <div class="media">
            <a class="pull-left" href="/video/{{$video->id}}">
                <img class="media-object" src="{{ $video->cover() }}" alt="{{ $video->title }}">
            </a>
            <div class="media-body">
                <h4 class="media-heading">{{ $video->title }}</h4>
                <p>{{ $video->description }}</p>
            </div>
        </div>
        @endforeach
    </div>

</div>
@stop

@push('scripts')
    @include('parts.js_for_app')
@endpush
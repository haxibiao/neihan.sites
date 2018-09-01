@extends('layouts.app')
@section('title')
    页面找不到 - {{ env('APP_NAME') }} 
@endsection 
@section('content')
<div class="container">
    <div class="jumbotron">
        <div class="container error">
            <img src="/images/404.png" alt="">
            <div class="info">
                <h2>很抱歉，你访问的页面不存在</h2>
                <p class="state">输入地址有误或该地址已被删除，你可以<a class="return" href="/">
                    返回首页
                </a>，也可以尝试我们为你推荐的有趣内容。</p>
                <i class="iconfont icon-icon-test"></i>
            </div>
            <div class="recommend">
                <div class="title">
                    <h3>{{ config('app.name') }}为你推荐</h3>
                </div>
                <div class="hot-recommend">
                    <span>精彩推荐：</span>
                    @foreach($data['categories'] as $category)
                        <a href="{{ url("/$category->name_en") }}">{{ $category->name }}</a>
                    @endforeach
                </div>
                <div class="video">
                    @foreach($data['articles'] as $article)
                        <div class="col-xs-6 col-md-3 video">
                            <div class="video-item vt"><div class="thumb">
                                <a href="{{ url("video/$article->video_id") }}" target="_blank">
                                    <img src="{{ $article->primaryImage() }}" alt="{{ $article->video->title ?: $article->get_description() }}"> 
                                    <i class="duration">{{ gmdate('i:s', $article->video->duration) }}</i> 
                                    <i class="hover-play"></i>
                                </a>
                            </div> 
                                <ul class="info-list">
                                    <li class="video-title"><a target="_blank" href="{{ url("video/$article->video_id") }}">{{ $article->video->title ?: $article->get_description() }}</a></li>
                                     <li>
                                        <p class="subtitle single-line">{{ $article->hits }}次播放</p>
                                    </li>
                                 </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

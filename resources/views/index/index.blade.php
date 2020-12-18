@extends('layouts.app')

@section('title')
    {{ seo_site_name() }}-{{ get_seo_title() }}
@stop

@section('keywords'){{ get_seo_keywords() }}
@stop

@section('description'){{ get_seo_description() }}
@stop

@section('content')

    <div id="index">
        {{-- 轮播图 --}}
        <div class="wrap clearfix">
            {{-- 主要内容 --}}
            <div class="main sm-left">
                <div class="poster-container">
                    @if (config('editor.ui.show_poster'))
                        @include('index.parts.poster')
                    @endif
                </div>
                {{-- 推荐专题 --}}
                @include('index.parts.recommend_categories')
				<recommend-category></recommend-category>
				
                {{-- top 4 videos --}}
                <div class="row videos distance">
                    <div class="vd-head">
                        <h3 class="vd-title">
                            <span class="title-icon">
                                <i class="iconfont icon-huo"></i>{{ seo_site_name() }}-合集
                            </span>
                        </h3>
                        <a href="/video" class="more-video">
                            <p>
                                更多
                                <i class="iconfont icon-youbian"></i>
                            </p>
                        </a>
                    </div>
                    @include('index.parts.video_posts', ['videoPosts' => $data->videoPosts])
                </div>
                {{-- 文章列表 --}}
                <ul class="article-list">
                    {{-- 置顶文章 --}}
                    @if (request('page') < 2)
                        @each('parts.article_item', get_stick_articles('发现'), 'article')
                    @endif
                    {{-- 文章 --}}
                    @each('parts.article_item', $data->articles, 'article')

                    {{-- 登录后才加载更多 --}}
                    @if (Auth::check())
                        <article-list api="/api/articles" start-page="2" is-desktop="{{ isDeskTop() == 1 }}" />
                    @else
                        <div>
                            {!! $data->articles->links() !!}
                        </div>
                    @endif

                </ul>
            </div>
            {{-- 侧栏 --}}
            <div class="aside sm-right hidden-xs">
                @include('index.parts.trendings')
                
				{{--  问答分类  --}}
				@if(isDeskTop())
					<div class="recommend-follower">
						<div class="plate-title">问答分类</div>
						@include('index.parts.recommend_questions')
					</div>
				@endif

				{{-- 下载APP --}}
				@include('index.parts.download_app')
				
                {{-- 日报 --}}
                {{-- @include('index.parts.daily') --}}
                {{-- 推荐作者 --}}
				<recommend-authors is-login="{{ Auth::check() ? true : false }}"></recommend-authors>
				
            </div>
        </div>
    </div>

@endsection

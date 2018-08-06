@extends('layouts.app')
@section('title')
    {{ $user->name }} - {{ env('APP_NAME') }}
@stop
@section('content')
<div id="user">
    <div id="user_wrp">
        <div class="clearfix">
            <div class="main sm-left">
                {{-- 用户信息 --}}
                @include('user.parts.information')
                {{-- 内容 --}}
                {{-- 内容/关注的专题文集，喜欢的作品 --}}
                <div class="content">
                    <!-- Nav tabs -->
                    <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                        <li role="presentation" {!! ends_with(request()->path(), 'followed-categories') ? 'class="active"' : '' !!}>
                            <a href="#categorys" aria-controls="categorys" role="tab" data-toggle="tab">关注的专题 {{ $data['followed_categories']->total() }}</a>
                        </li>
                        <li role="presentation" {!! ends_with(request()->path(), 'followed-collections') ? 'class="active"' : '' !!}>
                            <a href="#collection" aria-controls="collection" role="tab" data-toggle="tab">关注的文集 {{ $data['followed_collections']->total() }}</a>
                        </li>
                        <li role="presentation" {!! ends_with(request()->path(), 'likes') ? 'class="active"' : '' !!}>
                            <a href="#article" aria-controls="article" role="tab" data-toggle="tab">喜欢的作品 {{ $data['liked_articles']->total() }}</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="article-list tab-content">
                        <ul role="tabpanel" class="fade in note-list tab-pane {{ ends_with(request()->path(), 'followed-categories') ? 'active' : '' }}" id="categorys">

                        	@foreach($data['followed_categories'] as $follow)
                        	@php
                        		$category = $follow->followed;
                        	@endphp
                            @if($category)
                                @include('user.parts.follow_category_item', ['category'=>$category])
                            @endif
                            @endforeach
                            @if(Auth::check())
                                <follow-categories-list api="/api/user/{{ $user->id }}/follow?categories=1" start-page="2" not-empty="{{count($data['followed_categories'])>0}}" current-user-id="{{ user_id() }}">
                                </follow-categories-list>
                            @endif
                        </ul>
                        <ul role="tabpanel" class="fade in note-list tab-pane {{ ends_with(request()->path(), 'followed-collections') ? 'active' : '' }}" id="collection">

                            @foreach($data['followed_collections'] as $follow)
                            @php
                                $collection = $follow->followed;
                            @endphp
                            @if($collection)
                            <li class="note-info">
                                <a class="avatar-category" href="/collection/{{ $collection->id }}"><img src="/images/collection.png" alt=""></a>

                                <follow
                                    type="collections"
                                    id="{{ $collection->id }}"
                                    user-id="{{ user_id() }}"
                                    followed="{{ is_follow('collections', $collection->id) }}">
                                  </follow>

                                <div class="title">
                                    <a href="/collection/{{ $collection->id }}" class="name">{{ $collection->name }}</a>
                                </div>
                                <div class="info">
                                    <p><a href="/collection/{{ $collection->id }}">{{ $collection->name }} </a>收录了{{ $collection->count }}篇作品，{{ $collection->count_follows }}人关注</p>
                                </div>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                        <ul role="tabpanel" class="fade in article-list tab-pane  {{ ends_with(request()->path(), 'likes') ? 'active' : '' }}" id="article">

                            @foreach($data['liked_articles'] as $like)
                            	@include('parts.article_item', ['article' => $like->liked])
                            @endforeach
                            @if(Auth::check())
                                <article-list api="/user/{{ $user->id }}/likes" is-desktop="{{ \Agent::isDeskTop() == 1 }}" start-page="2">
                                </article-list>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
            {{-- 侧栏 --}}
            @include('user.parts.aside')
        </div>
    </div>
</div>
@stop
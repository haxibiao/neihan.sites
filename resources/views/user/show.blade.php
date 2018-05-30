@extends('layouts.app')

@section('title') {{ $user->name }} -{{ env('APP_NAME') }} @stop

@section('content')
<div id="user">
    <div class="clearfix">
        <div class="main sm-left">
            {{-- 用户信息 --}}
           @include('user.parts.information')
            {{-- 内容 --}}
            <div class="content">
                <!-- Nav tabs -->
                <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#article" aria-controls="article" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>文章</a>
                    </li>
                    <li role="presentation">
                        <a href="#dynamic" aria-controls="dynamic" role="tab" data-toggle="tab"><i class="iconfont icon-zhongyaogaojing"></i>动态</a>
                    </li>
                    <li role="presentation">
                        <a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>最新评论</a>
                    </li>
                    <li role="presentation">
                        <a href="#hot" aria-controls="hot" role="tab" data-toggle="tab"><i class="iconfont icon-huo"></i>热门</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="article-list tab-content">
                    <ul role="tabpanel" class="fade in note-list tab-pane active" id="article">
                        @each('parts.article_item', $data['articles'], 'article')
                        @if(Auth::check())
                        <article-list api="/user/{{ $user->id }}?articles=1" start-page="2" />
                        @else
                        <div>{!! $data['articles']->links() !!}</div>
                        @endif
                    </ul>
                    {{-- 动态 --}}
                    <ul role="tabpanel" class="fade feed-list tab-pane" id="dynamic">
                        @each('user.parts.action_item', $data['actions'], 'action')

                        {{-- 加入时间 --}}
                        <li class="feed-info distance">
                            <div class="content">
                                <div class="author">
                                    <a class="avatar" href="/user/{{ $user->id }}">
                                            <img src="{{ $user->avatar() }}" alt="">
                                                </a>
                                    <div class="info">
                                        <a class="nickname" target="_blank" href="/user/{{ $user->id }}">{{ $user->name }}</a>
                                        {{-- <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt=""> --}}
                                        <span class="time"> 加入了{{ config('app.name') }} · {{ $user->created_at }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul role="tabpanel" class="fade note-list tab-pane" id="comment">
                        @each('parts.article_item', $data['commented'], 'article')
                        @if(Auth::check())
                        <article-list api="/user/{{ $user->id }}?commented=1" start-page="2" />
                        @else
                        <div>{!! $data['commented']->links() !!}</div>
                        @endif
                    </ul>
                    <ul role="tabpanel" class="fade note-list tab-pane" id="hot">
                        @each('parts.article_item', $data['hot'], 'article')
                        @if(Auth::check())
                        <article-list api="/user/{{ $user->id }}?hot=1" start-page="2" />
                        @else
                        <div>{!! $data['hot']->links() !!}</div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        {{-- 侧栏 --}}
        @include('user.parts.aside')
    </div>
</div>
@endsection

@push('modals')
    <modal-blacklist></modal-blacklist>
    <modal-report></modal-report>
@endpush
@extends('layouts.app')

@section('title')
    爱你城
@stop
@section('content')
<div id="home">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                <div class="main_top clearfix">
                    <a class="avatar" href="/user/{{ Auth::id() }}">
                        <img src="{{ $user->avatar }}"/>
                    </a>

                    @if(!$user->isSelf())
                        @if(Auth::check())
                        <follow type="users" id="{{ $user->id }}" user-id="{{ Auth::user()->id }}" followed="{{ Auth::user()->isFollow('users', $user->id)}}"></follow>
                        @endif
                        
                            <a class="botm contribute" href="/chat/with/{{ $user->id }}">
                                <span>
                                    发消息
                                </span>
                            </a>

                    <div class="title">
                        <a class="name" href="/user/{{ Auth::id() }}">
                            <span>
                                {{ $user->name }}
                            </span>
                            <i class="iconfont icon-nvsheng1">
                            </i>
                        </a>
                    </div>
                    @endif
                    <div class="info">
                        <ul>
                            <li>
                                <div class="meta_block">
                                    <a href="#">
                                        <p>
                                            {{ $user->count_follows }}
                                        </p>
                                        关注
                                        <i class="iconfont icon-youbian">
                                        </i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <a href="#">
                                        <p>
                                            {{ $user->count_actions }}
                                        </p>
                                        动态
                                        <i class="iconfont icon-youbian">
                                        </i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <a href="/v1/home">
                                        <p>
                                             {{ $user->count_articles }}
                                        </p>
                                        文章
                                        <i class="iconfont icon-youbian">
                                        </i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <p>
                                             {{ $user->count_words }}
                                    </p>
                                    <div>
                                        字数
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <p>
                                             {{ $user->count_likes }}
                                    </p>
                                    <div>
                                        收获喜欢
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div>
                    <!-- Nav tabs -->
                    <ul class="trigger_menu" role="tablist">
                        <li role="presentation" class="active" >
                            <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                                <i class="iconfont icon-wenji">
                                </i>
                                文章
                            </a>
                        </li>
                        <li role="presentation">
                            <a aria-controls="dongtai" data-toggle="tab" href="#dongtai" role="tab">
                                <i class="iconfont icon-zhongyaogaojing">
                                </i>
                                动态
                            </a>
                        </li>
                        <li role="presentation">
                            <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                                <i class="iconfont icon-svg37">
                                </i>
                                最新评论
                            </a>
                        </li>
                        <li role="presentation">
                            <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                                <i class="iconfont icon-huo">
                                </i>
                                热门
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="wenzhang" role="tabpanel">
                             @include('parts.list.article_list_category',['articles'=>$data['articles']])
                        </div>
                        <div class="tab-pane fade" id="dongtai" role="tabpanel">
                            <ul class="article_list">
                              @include('user.parts.user_acive')
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="pinglun" role="tabpanel">
                            @include('parts.list.article_list_category',['articles'=>$data['commented']])
                        </div>
                        <div class="tab-pane fade" id="huo" role="tabpanel">
                            @include('parts.list.article_list_category',['articles'=>$data['hot']])
                        </div>
                    </div>
                </div>
            </div>
            <div class="aside col-sm-4">
                <div class="title">
                    个人介绍
                </div>
                <a class="function_btn" href="javascript:;">
                    <i class="iconfont icon-xie">
                    </i>
                    编辑
                </a>
                <div class="description">
                    <div class="intro">
                        {{ $user->introduction }}
                    </div>
                </div>
                <ul class="user_dynamic">
                    <li>
                        <a href="#">
                            <i class="iconfont icon-duoxuan">
                            </i>
                            <span>
                                关注的专题/文集
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="iconfont icon-xin">
                            </i>
                            <span>
                                喜欢的文章
                            </span>
                        </a>
                    </li>
                </ul>
                <div>
                    <p class="title">
                        创建的专题
                    </p>
                    <ul class="list">
                          @each('user.parts.category_item', $user->categories()->orderBy('id','desc')->take(5)->get(), 'category')
                    </ul>
                    <p class="title">
                        我的文集
                    </p>
                    <ul class="list">
                          @each('user.parts.collection_item', $user->collections()->orderBy('id','desc')->take(5)->get(), 'collection')
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

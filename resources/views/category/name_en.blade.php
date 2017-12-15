@extends('layouts.app')

@section('title')
    谈谈情，说说爱 - 专题 - 爱你城
@stop
@section('content')
<div id="category">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                <div class="main_top">
                    <a class="avatar avatar_collection" href="/v1/category">
                        <img src="{{ $category->logo }}"/>
                    </a>

                {{-- <a class="botm follow" href="#"> --}}
                       <follow 
                        type="categories" 
                        id="{{ $category->id }}" 
                        user-id="{{ Auth::check() ? Auth::user()->id : false }}" 
                        followed="{{ Auth::check() ? Auth::user()->isFollow('categories', $category->id) : false }}">
                      </follow>
                    {{-- </a> --}}
                    <a class="botm contribute" href="#">
                        <span>
                            投稿
                        </span>
                    </a>

                    <div class="title">
                        <a class="name" href="/{{ $category->name_en }}">
                            <span>
                                {{ $category->name }}
                            </span>
                        </a>
                    </div>
                    <p>
                        收录了{{ $category->articles->count() }}篇文章 · {{ $category->count_follows }}人关注
                    </p>
                </div>
                <div>
                    <!-- Nav tabs -->
                    <ul class="trigger_menu" role="tablist">
                        <li role="presentation">
                            <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                                <i class="iconfont icon-svg37">
                                </i>
                                最新评论
                            </a>
                        </li>
                        <li class="active" role="presentation">
                            <a aria-controls="shoulu" data-toggle="tab" href="#shoulu" role="tab">
                                <i class="iconfont icon-wenji">
                                </i>
                                最新收录
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
                        <div class="tab-pane fade in active" id="pinglun" role="tabpanel">
                            @include('category.parts.category_item',['articles'=>$data['commented']])
                        </div>
                        <div class="tab-pane fade" id="shoulu" role="tabpanel">
                            @include('category.parts.category_item',['articles'=>$data['collected']])
                        </div>
                        <div class="tab-pane fade" id="huo" role="tabpanel">
                            @include('category.parts.category_item',['articles'=>$data['hot']])
                        </div>
                    </div>
                </div>
            </div>
            <div class="aside col-sm-4">
                <p class="title">
                    专题公告
                </p>
                <div class="description">
                      {{ $category->description }}
                <div class="share">
                    <span>
                        分享到
                    </span>
                    <a href="#">
                        <i class="iconfont icon-weixin1">
                        </i>
                    </a>
                    <a href="#">
                        <i class="iconfont icon-sina">
                        </i>
                    </a>
                    <a href="#">
                        <i class="iconfont icon-gengduo">
                        </i>
                    </a>
                </div>
                <div class="intendant">
                    <div>
                        <p class="title">
                            管理员
                        </p>
                        <ul class="collection_editor">
                          @foreach($category->topAdmins() as $admin)
                            <li>
                                <a class="avatar" href="/user/{{ $admin->id }}">
                                    <img src="{{ $admin->avatar }}"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    {{ $admin->name }}
                                </a>
                                @if($admin->isCreator)
                                <span class="tag">
                                    创建者
                                </span>
                                @endif
                            </li>
                          @endforeach
                        </ul>
                    </div>
                    <div>
                        <div class="title">
                            推荐作者({{ $category->topAuthors()->count() }})
                            <i class="iconfont icon-tuijian1">
                            </i>
                        </div>
                        <ul class="collection_follower">
                          @foreach($category->topAuthors() as $user)
                            <li>
                                <a class="avatar" href="/user/{{ $user->id }}">
                                    <img src="{{ $user->avatar }}"/>
                                </a>
                            </li>
                           @endforeach
                        </ul>
                    </div>
                    <div>
                        <div class="title">
                            关注的人
                        </div>
                        <ul class="collection_follower">
                          @foreach($category->topFollowers() as $user)
                            <li>
                                <a class="avatar" href="/user/{{ $user->id }}">
                                    <img src="{{ $user->avatar }}"/>
                                </a>
                            </li>
                          @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

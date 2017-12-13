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
                        <img src="/images/category_02.jpg"/>
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
                        收录了{{ $category->articles->count() }}篇文章 · 1081552人关注
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
                    <p>
                        柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度认真就好
                        <br/>
                        如果你想分享自己或者身边人的爱情故事，欢迎前来投稿
                    </p>
                    <p>
                        投稿须知：
                        <br/>
                        1.本专题仅收录关于爱情的文章。注意：爱情类小说除非足够精彩然后不是连载，不然不在收录范围。另外关于名人伟人的爱情故事也请改投相关专题
                        <br/>
                        2.第一条中爱情类小说的“精彩”，是指文章有小说的基本结构，运用了基本写法，语言流畅，情节动人。诸如Ａ说Ｂ又说这样毫无人物描写、场景描写的文章就请改投其他相关专题。
                        <br/>
                        3.请保证文章质量和基本排版，勿出...
                    </p>
                    <a href="javascript:">
                        展开描述
                        <i class="iconfont icon-xia">
                        </i>
                    </a>
                </div>
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
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    爱你城
                                </a>
                                <span class="tag">
                                    创建者
                                </span>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    甜腻酥饼
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    周寒舟
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    枫小梦
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    木小溪V
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    木禾的随笔
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    青烟幂处
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    二野童
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    沈家姑娘
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    桃宜
                                </a>
                            </li>
                            <li>
                                <a class="check_more" href="#">
                                    展开更多
                                    <i class="iconfont icon-xia">
                                    </i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <div class="title">
                            推荐作者(100)
                            <i class="iconfont icon-tuijian1">
                            </i>
                        </div>
                        <ul class="collection_follower">
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <a class="function_btn" href="#">
                                <i class="iconfont icon-gengduo">
                                </i>
                            </a>
                        </ul>
                    </div>
                    <div>
                        <div class="title">
                            关注的人(1092324)
                        </div>
                        <ul class="collection_follower">
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <a class="function_btn" href="#">
                                <i class="iconfont icon-gengduo">
                                </i>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@extends('v1.layouts.app')

@section('title')
    日记本 - 文集 - 爱你城
@stop
@section('content')
<div id="collection">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                <div class="main_top">
                    <a class="avatar avatar_collection" href="/v1/collection">
                        <img src="/images/category_09.png"/>
                    </a>
                    <a class="follow" href="#">
                        <span>
                            ＋ 关注
                        </span>
                    </a>
                    <div class="title">
                        <a class="name" href="/v1/collection">
                            <span>
                                王者荣耀
                            </span>
                        </a>
                    </div>
                    <p>
                        19篇文章 · 27123字 · 1人关注
                    </p>
                </div>
                <div>
                    <!-- Nav tabs -->
                    <ul class="trigger_menu" role="tablist">
                        <li role="presentation">
                            <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                                <i class="iconfont icon-wenji">
                                </i>
                                最新发布
                            </a>
                        </li>
                        <li class="active" role="presentation">
                            <a aria-controls="shoulu" data-toggle="tab" href="#shoulu" role="tab">
                                <i class="iconfont icon-svg37">
                                </i>
                                最新评论
                            </a>
                        </li>
                        <li role="presentation">
                            <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                                <i class="iconfont icon-duoxuan">
                                </i>
                                目录
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="pinglun" role="tabpanel">
                            @include('v1.parts.article_list_nocategory')
                        </div>
                        <div class="tab-pane fade" id="shoulu" role="tabpanel">
                            @include('v1.parts.article_list_nocategory')
                        </div>
                        <div class="tab-pane fade" id="huo" role="tabpanel">
                            @include('v1.parts.article_list_nocategory')
                        </div>
                    </div>
                </div>
            </div>
            <div class="aside col-sm-4">
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
                        <i class="iconfont icon-qq1">
                        </i>
                    </a>
                </div>
                <div class="intendant">
                    <div>
                        <p class="title">
                            文集作者
                        </p>
                        <ul class="collection_editor">
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    爱你城精选
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

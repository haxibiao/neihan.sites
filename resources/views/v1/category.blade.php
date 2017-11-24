@extends('v1.layouts.app')

@section('title')
    谈谈情，说说爱 - 专题 - 爱你城
@stop
@section('content')
<div id="category">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                <div class="main_top clearfix">
                    <a class="avatar" href="/v1/category">
                        <img src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                    </a>
                    <a class="botm follow" href="#">
                        <i class="iconfont icon-jia">
                        </i>
                        <span>
                            关注
                        </span>
                    </a>
                    <a class="botm contribute" href="#">
                        <span>
                            投稿
                        </span>
                    </a>
                    <a class="name" href="/v1/category">
                        <span>
                            谈谈情，说说爱
                        </span>
                    </a>
                    <p>
                        收录了68923篇文章 · 1081552人关注
                    </p>
                </div>
                <div>
                    <!-- Nav tabs -->
                    <ul class="trigger_menu" role="tablist">
                        <li role="presentation">
                            <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                                <i class="iconfont icon-pinglun">
                                </i>
                                最新评论
                            </a>
                        </li>
                        <li class="active" role="presentation">
                            <a aria-controls="shoulu" data-toggle="tab" href="#shoulu" role="tab">
                                <i class="iconfont icon-dongtai1">
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
                            @include('v1.parts.writing')
                        </div>
                        <div class="tab-pane fade" id="shoulu" role="tabpanel">
                            @include('v1.parts.writing')
                        </div>
                        <div class="tab-pane fade" id="huo" role="tabpanel">
                            @include('v1.parts.writing')
                        </div>
                    </div>
                </div>
            </div>
            <div class="aside col-sm-4 col-lg-3 col-lg-offset-1">
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
                        <i class="iconfont icon-xinlangweibo">
                        </i>
                    </a>
                    <a href="#">
                        <i class="iconfont icon-weixin-copy">
                        </i>
                    </a>
                    <a href="#">
                        <i class="iconfont icon-sandian-copy">
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
                                    <img src="/logo/ainicheng.com.jpg"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    爱你城
                                </a>
                                <span>
                                    创建者
                                </span>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/4495513/b0433c10-21db-4e8e-938c-6dd4618297fd.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    甜腻酥饼
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/1694433/6e122981-342e-4815-9a07-aba78ca30645.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    周寒舟
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/1122063/fb48cf06757d?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    枫小梦
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/1610007/d29da9456083.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    木小溪V
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/2239737/e2d25096-c6c8-499d-97dd-143fe74794bb.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    木禾的随笔
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/3067977/1bbdf808-332d-434d-aa2b-ebfac5c8882b.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    青烟幂处
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/3203762/ad383e98-8cfe-4a32-9022-9c0037b527f4.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    二野童
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/1531089/3c04b9d0-cd6d-4ad8-8467-137f08fbfbac.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="name" href="/v1/user">
                                    沈家姑娘
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="//upload.jianshu.io/users/upload_avatars/3241851/4f378a2f4352.jpeg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
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
                            <i class="iconfont icon-yiwen">
                            </i>
                        </div>
                        <ul class="collection_follower">
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/5479122/9201a33e-d57d-4347-bfd4-7d3758bcab47.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/2719544/78e263be1f3d.jpeg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/2674946/efbe3aa1-016e-4f39-9163-36368d41f54a.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/2251594/caab597c-f17c-4fda-82d5-57fdacbce93d.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/3525563/0622b29b-5823-494a-8698-dd2577ada955.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/3364033/a9dd54fe-26f5-4857-9dc1-86cf0b01d93e.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/4960058/3dd42a01-71c0-49a4-b22c-b00d5804b271.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/1946430/0ad43fe1-4aac-44be-b2d3-fd699e81a541.jpg"/>
                                </a>
                            </li>
                            <a class="function_btn" href="#">
                                <i class="iconfont icon-sandian">
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
                                    <img src="http://upload.jianshu.io/users/upload_avatars/5479122/9201a33e-d57d-4347-bfd4-7d3758bcab47.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/2719544/78e263be1f3d.jpeg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/2674946/efbe3aa1-016e-4f39-9163-36368d41f54a.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/2251594/caab597c-f17c-4fda-82d5-57fdacbce93d.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/3525563/0622b29b-5823-494a-8698-dd2577ada955.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/3364033/a9dd54fe-26f5-4857-9dc1-86cf0b01d93e.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/4960058/3dd42a01-71c0-49a4-b22c-b00d5804b271.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="http://upload.jianshu.io/users/upload_avatars/1946430/0ad43fe1-4aac-44be-b2d3-fd699e81a541.jpg"/>
                                </a>
                            </li>
                            <a class="function_btn" href="#">
                                <i class="iconfont icon-sandian">
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

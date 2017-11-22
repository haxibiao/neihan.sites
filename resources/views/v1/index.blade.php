@extends('v1.layouts.app')

@section('title')
    爱你城 - 最暖心的游戏社交网站
@stop
@section('content')
<div id="index">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                <div class="carousel_inner">
                    <div class="carousel slide" data-ride="carousel" id="carousel-example-generic">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li class="active" data-slide-to="0" data-target="#carousel-example-generic">
                            </li>
                            <li data-slide-to="1" data-target="#carousel-example-generic">
                            </li>
                            <li data-slide-to="2" data-target="#carousel-example-generic">
                            </li>
                        </ol>
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <img src="/logo/22.jpeg"/>
                                <div class="carousel-caption">
                                </div>
                            </div>
                            <div class="item">
                                <img src="/logo/23.jpeg"/>
                                <div class="carousel-caption">
                                </div>
                            </div>
                            <div class="item">
                                <img src="/logo/24.jpeg"/>
                                <div class="carousel-caption">
                                </div>
                            </div>
                        </div>
                        <!-- Controls -->
                        <a class="left carousel-control" data-slide="prev" href="#carousel-example-generic" role="button">
                            <span aria-hidden="true" class="glyphicon glyphicon-chevron-left">
                            </span>
                            <span class="sr-only">
                                Previous
                            </span>
                        </a>
                        <a class="right carousel-control" data-slide="next" href="#carousel-example-generic" role="button">
                            <span aria-hidden="true" class="glyphicon glyphicon-chevron-right">
                            </span>
                            <span class="sr-only">
                                Next
                            </span>
                        </a>
                    </div>
                </div>
                <div class="classification">
                    <a class="collection" href="/v1/category">
                        <img src="/logo/col-01.jpg"/>
                        <div class="name">
                            王者荣耀
                        </div>
                    </a>
                    <a class="collection" href="/v1/category">
                        <img src="/logo/col-01.jpg"/>
                        <div class="name">
                            绝地求生
                        </div>
                    </a>
                    <a class="collection" href="/v1/category">
                        <img src="/logo/col-01.jpg"/>
                        <div class="name">
                            剑侠情缘3
                        </div>
                    </a>
                    <a class="collection" href="/v1/category">
                        <img src="/logo/1.jpeg"/>
                        <div class="name">
                            暗恋
                        </div>
                    </a>
                    <a class="collection" href="/v1/category">
                        <img src="/logo/1.jpeg"/>
                        <div class="name">
                            恋爱
                        </div>
                    </a>
                    <a class="collection" href="/v1/category">
                        <img src="/logo/1.jpeg"/>
                        <div class="name">
                            热恋
                        </div>
                    </a>
                    <a class="collection" href="/v1/category">
                        <img src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                        <div class="name">
                            谈谈情，说说爱
                        </div>
                    </a>
                    <a class="collection more_hot_collection" href="/v1/categories">
                        <div class="name">
                            更多热门专题
                            <i class="iconfont icon-gengduo">
                            </i>
                        </div>
                    </a>
                </div>
                @include('v1.parts.index_article_block')
            </div>
            <div class="aside col-sm-4 col-lg-3 col-lg-offset-1">
                <form class="search">
                    <div class="input-group">
                        <input class="form-control" placeholder="搜索" type="text"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="glyphicon glyphicon-search">
                                </i>
                            </button>
                        </span>
                    </div>
                </form>
                <div class="board clearfix">
                    <a class="sign col-xs-12" href="/v1/category">
                        <span>
                            每日一签
                            <i class="iconfont icon-gengduo">
                            </i>
                        </span>
                    </a>
                    <a class="newlist col-xs-12" href="/v1/category">
                        <span>
                            新上榜
                            <i class="iconfont icon-gengduo">
                            </i>
                        </span>
                    </a>
                    <a class="game col-xs-12" href="/v1/category">
                        <span>
                            游戏热门
                            <i class="iconfont icon-gengduo">
                            </i>
                        </span>
                    </a>
                    <a class="love col-xs-12" href="/v1/category">
                        <span>
                            恋爱热门
                            <i class="iconfont icon-gengduo">
                            </i>
                        </span>
                    </a>
                </div>
                <div class="codes clearfix">
                    <a class="col-xs-12" href="javascript:;">
                        <span>
                            <img src="/logo/erweima1.jpeg"/>
                            <p>
                                下载爱你城手机App
                                <i class="iconfont icon-gengduo">
                                </i>
                                <br/>
                                <small>
                                    随时随地发现和创作内容
                                </small>
                            </p>
                        </span>
                    </a>
                </div>
                <div class="daily">
                    <div class="title">
                        <span>
                            爱你城精章
                        </span>
                        <a href="/v1/category">
                            查看更多
                        </a>
                    </div>
                    <a class="note" href="/v1/detail">
                        <img src="http://upload-images.jianshu.io/upload_images/1714520-119e82e1662d86ac.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240"/>
                        <div class="note_title">
                            恋爱要谈到什么程度，才适合结婚呢？
                        </div>
                    </a>
                    <a class="note" href="/v1/detail">
                        <div class="note_title">
                            三个心理学语言技巧，让你迅速提高情商
                        </div>
                    </a>
                </div>
                <div class="recommended_authors">
                    <div class="title">
                        <span>
                            推荐作者
                        </span>
                        <a href="javascript:;">
                            <i class="glyphicon glyphicon-refresh">
                            </i>
                            换一批
                        </a>
                    </div>
                    <ul class="list">
                        <li>
                            <a class="avatar" href="/v1/user">
                                <img src="//upload.jianshu.io/users/upload_avatars/19107/08f8146dae87.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                <i class="iconfont icon-jia">
                                </i>
                                关注
                            </a>
                            <a class="name" href="/v1/user">
                                王佩
                            </a>
                            <p>
                                写了400.9k字 · 13.8k喜欢
                            </p>
                        </li>
                        <li>
                            <a class="avatar" href="/v1/user">
                                <img src="//upload.jianshu.io/users/upload_avatars/6287/06c537002583.png?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                <i class="iconfont icon-jia">
                                </i>
                                关注
                            </a>
                            <a class="name" href="/v1/user">
                                刘淼
                            </a>
                            <p>
                                写了375.5k字 · 20.5k喜欢
                            </p>
                        </li>
                        <li>
                            <a class="avatar" href="/v1/user">
                                <img src="//upload.jianshu.io/users/upload_avatars/1996705/738ba2908445?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                <i class="iconfont icon-jia">
                                </i>
                                关注
                            </a>
                            <a class="name" href="/v1/user">
                                白发老籣
                            </a>
                            <p>
                                写了50.5k字 · 5.7k喜欢
                            </p>
                        </li>
                        <li>
                            <a class="avatar" href="/v1/user">
                                <img src="//upload.jianshu.io/users/upload_avatars/6198903/a70dc654-6674-4b71-925f-0389f31fb095.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                <i class="iconfont icon-jia">
                                </i>
                                关注
                            </a>
                            <a class="name" href="/v1/user">
                                魏童
                            </a>
                            <p>
                                写了39.4k字 · 1.4k喜欢
                            </p>
                        </li>
                        <li>
                            <a class="avatar" href="/v1/user">
                                <img src="//upload.jianshu.io/users/upload_avatars/7663825/7c28763e-002b-4e89-8dea-5b8da210ef2c.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                <i class="iconfont icon-jia">
                                </i>
                                关注
                            </a>
                            <a class="name" href="/v1/user">
                                名贵的考拉熊
                            </a>
                            <p>
                                写了104.3k字 · 8.3k喜欢
                            </p>
                        </li>
                    </ul>
                    <a class="find_more" href="/v1/categories">
                        查看全部
                        <i class="iconfont icon-gengduo">
                        </i>
                    </a>
                </div>
                <div class="videos">
                    <div class="titles">
                        <span>
                            热门视频
                        </span>
                        <a href="javascript:;">
                            查看更多
                        </a>
                    </div>
                    <a class="videos_list" href="/v1/detail">
                        <div class="screenshot">
                            <img src="https://ainicheng.com/storage/img/1806.jpeg"/>
                        </div>
                        <div class="list_title">
                            王者荣耀打野必备攻略 5v5野区地图分布详解
                        </div>
                    </a>
                    <a class="videos_list" href="/v1/detail">
                        <div class="screenshot">
                            <img src="https://ainicheng.com/storage/img/1890.png"/>
                        </div>
                        <div class="list_title">
                            王者荣耀最强奶妈蔡文姬怎么玩2.0加强版
                        </div>
                    </a>
                    <a class="videos_list" href="/v1/detail">
                        <div class="screenshot">
                            <img src="https://ainicheng.com/storage/img/1840.jpg"/>
                        </div>
                        <div class="list_title">
                            手把手教你玩王者荣耀安琪拉
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('v1.parts.foot')
@stop

@extends('v1.layouts.blank')

@section('title')
    空评 - 爱你城
@stop
@section('content')
<div id="user">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                <div class="main_top clearfix">
                    <a class="avatar" href="/v1/user">
                        <img src="//upload.jianshu.io/users/upload_avatars/4896574/81748b90-d20d-40fd-a659-127ece846249?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
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
                            发消息
                        </span>
                    </a>
                    <div class="title">
                        <a class="name" href="/v1/user">
                            <span>
                                空评
                            </span>
                            <i class="iconfont icon-nanhai">
                            </i>
                        </a>
                    </div>
                    <div class="info">
                        <ul>
                            <li>
                                <div class="meta_block">
                                    <a href="#">
                                        <p>
                                            5
                                        </p>
                                        关注
                                        <i class="iconfont icon-gengduo">
                                        </i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <a href="#">
                                        <p>
                                            6
                                        </p>
                                        粉丝
                                        <i class="iconfont icon-gengduo">
                                        </i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <a href="#">
                                        <p>
                                            8
                                        </p>
                                        文章
                                        <i class="iconfont icon-gengduo">
                                        </i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <p>
                                        17299
                                    </p>
                                    <div>
                                        字数
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <p>
                                        16
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
                        <li class="active" role="presentation">
                            <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                                <i class="iconfont icon-dongtai1">
                                </i>
                                文章
                            </a>
                        </li>
                        <li role="presentation">
                            <a aria-controls="dongtai" data-toggle="tab" href="#dongtai" role="tab">
                                <i class="iconfont icon-dongtai">
                                </i>
                                动态
                            </a>
                        </li>
                        <li role="presentation">
                            <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                                <i class="iconfont icon-pinglun">
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
                        <div class="tab-pane active" id="wenzhang" role="tabpanel">
                            @include('v1.parts.writing')
                        </div>
                        <div class="tab-pane" id="dongtai" role="tabpanel">
                            <ul class="list_container">
                                <li>
                                    <div class="personal">
                                        <a class="avatar" href="/v1/user">
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/4896574/81748b90-d20d-40fd-a659-127ece846249?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                空评
                                            </a>
                                            <small>
                                                发表了文章 · 06.15.12:12
                                            </small>
                                        </div>
                                    </div>
                                    <div class="substance have_img clearfix">
                                        <div class="conten col-xs-12 col-sm-8 col-md-9">
                                            <a href="/v1/detail">
                                                魔兽世界7.2.5全新版本资料片：新橙戒+新黑庙+乐队活动
                                            </a>
                                            <p class="article">
                                                《魔兽世界》7.2.5版本今日在国服上线。虽然是小版本更新，但改动内容还是十分丰富的。比如新的橙装、大秘境调整、黑庙加入时空漫游、克罗米战役等等。以下是17173为各位整理的《魔兽世界》7.2.5版本今日在国服上线。虽然是小版本更新，但改动内容还是十分丰富的。比如新的橙装、大秘境调整、黑庙加入时空漫游、克罗米战役等等。以下是17173为各位整理的
                                            </p>
                                        </div>
                                        <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="/v1/detail">
                                            <img src="//upload-images.jianshu.io/upload_images/4896574-5091b316532c0d99?imageMogr2/auto-orient/strip|imageView2/1/w/150/h/120"/>
                                        </a>
                                        <div class="statistics col-xs-12">
                                            <a href="/v1/detail">
                                                <i class="iconfont icon-yanjing">
                                                </i>
                                                717
                                            </a>
                                            <a href="/v1/detail">
                                                <i class="iconfont icon-weibiaoti5">
                                                </i>
                                                6
                                            </a>
                                            <span>
                                                <i class="iconfont icon-xihuan1">
                                                </i>
                                                13
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="personal">
                                        <a class="avatar" href="/v1/user">
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/4896574/81748b90-d20d-40fd-a659-127ece846249?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                空评
                                            </a>
                                            <small>
                                                关注了作者 · 06.14.23:55
                                            </small>
                                        </div>
                                    </div>
                                    <div class="follow_detail">
                                        <div class="info">
                                            <a class="avatar" href="#">
                                                <img src="//upload.jianshu.io/users/upload_avatars/1159369/799d8fa18062?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                            </a>
                                            <a class="follow" href="#">
                                                <i class="iconfont icon-jia">
                                                </i>
                                                <span>
                                                    关注
                                                </span>
                                            </a>
                                            <a class="title" href="#">
                                                Dreamover1010
                                            </a>
                                            <p>
                                                写了 16299 字，被 90 人关注，获得了 140 个喜欢
                                            </p>
                                        </div>
                                        <div class="signature">
                                            在广告行业摸爬滚打几年的设计师逃出升天
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="personal">
                                        <a class="avatar" href="/v1/user">
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/4896574/81748b90-d20d-40fd-a659-127ece846249?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                空评
                                            </a>
                                            <small>
                                                发表了评论 · 10.30.13:26
                                            </small>
                                        </div>
                                    </div>
                                    <div class="comment">
                                        <p>
                                            <a href="#">
                                                @夜_2d81
                                            </a>
                                            丧尸从0到1%，这个过程，军队就开始介入了，基本不可能达到99%
                                        </p>
                                        <blockquote>
                                            <a class="title" href="#">
                                                末日来临，最正确的丧尸自救指南
                                            </a>
                                            <p class="abstract">
                                                这是智先生的第8篇原创文章 当末日来临 你准备好了吗？ 最近我看完了马克斯·布鲁克斯写的《僵尸生存指南》，书本教会了人们如何在丧尸横行的世界中生存，并提供了从选择武器到制定逃...
                                            </p>
                                            <div class="meta">
                                                <div class="origin_author">
                                                    <a href="#">
                                                        空评
                                                    </a>
                                                </div>
                                                <a href="/v1/detail">
                                                    <i class="iconfont icon-yanjing">
                                                    </i>
                                                    1649
                                                </a>
                                                <a href="/v1/detail">
                                                    <i class="iconfont icon-weibiaoti5">
                                                    </i>
                                                    22
                                                </a>
                                                <span>
                                                    <i class="iconfont icon-xihuan1">
                                                    </i>
                                                    72
                                                </span>
                                            </div>
                                        </blockquote>
                                    </div>
                                </li>
                                <li>
                                    <div class="personal">
                                        <a class="avatar" href="/v1/user">
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/4896574/81748b90-d20d-40fd-a659-127ece846249?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                空评
                                            </a>
                                            <em>
                                                ·
                                            </em>
                                            <small>
                                                赞了评论 · 10.30.13:26
                                            </small>
                                        </div>
                                    </div>
                                    <div class="comment">
                                        <p>
                                            为这篇文章注册简书，我也挺难受大学寝室的环境的，本身自己挺孤僻，习惯一个人待着，最不能忍的是大二的洗澡是十几个开放式没有门的洗澡间···庆幸室友的性格都挺好，现在毕业两年还在联系，今天还和其中一个说想给她设计婚礼请帖的图案的，我玩的很好的高中同学大学5年的室友则是比较针对她，好像是因为拿奖学金之类的事吧，然后处处挤兑，她研究生的室友则是家教啥的都很好，她现在可开心了 我也很替她开心，她读研和我工作在一个城市
                                        </p>
                                        <blockquote>
                                            <div class="meta">
                                                <div class="origin_author">
                                                    <a href="#">
                                                        郭璐Alu
                                                    </a>
                                                    <span>
                                                        评论自
                                                        <a href="#">
                                                            集体宿舍，将3000万大学生拉入深渊
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane" id="pinglun" role="tabpanel">
                            @include('v1.parts.writing')
                        </div>
                        <div class="tab-pane" id="huo" role="tabpanel">
                            @include('v1.parts.writing')
                        </div>
                    </div>
                </div>
            </div>
            <div class="aside col-sm-4 col-lg-3 col-lg-offset-1">
                <ul class="user_dynamic">
                    <li>
                        <a href="#">
                            <img src="//cdn2.jianshu.io/assets/badges/signed-9702260821906f0d953eab67a29f8e7a2d2e3d20960576347591283a3fbfd867.png"/>
                            爱你城签约作者
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="//cdn2.jianshu.io/assets/badges/excellent-34cdda4de26ee9910f190239ffecf886c24045136468fa26f041b0156a143cd9.png"/>
                            空评《好中文的样子》
                        </a>
                    </li>
                </ul>
                <p class="title">
                    个人介绍
                </p>
                <div class="description">
                    <p>
                        十分精力：四分读书，三分写书，三分教书。《正版语文》作者，21世纪最牛写作书 《风格感觉》（The Sense of Style）中文译者之一，“好中文的样子”创办人和主讲人。
                    </p>
                    <a href="javascript:">
                        <i class="iconfont icon-xinlangweibo1">
                        </i>
                    </a>
                    <a href="javascript:">
                        <i class="iconfont icon-weixin">
                        </i>
                    </a>
                </div>
                <ul class="user_dynamic">
                    <li>
                        <a href="#">
                            <i class="iconfont icon-combinedshape">
                            </i>
                            <span>
                                他关注的专题/文集
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="iconfont icon-xihuan">
                            </i>
                            <span>
                                他喜欢的文章
                            </span>
                        </a>
                    </li>
                </ul>
                <div>
                    <p class="title">
                        他管理的专题
                    </p>
                    <ul class="list">
                        <li>
                            <a href="#">
                                <img src="//upload.jianshu.io/collections/images/367981/android.graphics.Bitmap_2bebf481.jpeg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                <span>
                                    文章
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <img src="//upload.jianshu.io/collections/images/319558/1.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                <span>
                                    好中文的样子
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="title">
                        他的文集
                    </p>
                    <ul class="list">
                        <li>
                            <a href="#">
                                <i class="iconfont icon-wenji">
                                </i>
                                <span>
                                    日记本
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="iconfont icon-wenji">
                                </i>
                                <span>
                                    好中文的样子
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="iconfont icon-wenji">
                                </i>
                                <span>
                                    个人试笔
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="user_action">
                    <a href="#">
                        加入黑名单
                    </a>
                    <em>
                        ·
                    </em>
                    <a href="#">
                        举报用户
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

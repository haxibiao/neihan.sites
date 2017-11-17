@extends('v1.layouts.blank')

@section('title')
    爱你城
@stop
@section('content')
<div id="home">
    <div class="container">
        <div class="row">
            <div class="essays col-xs-12 col-sm-8">
                <div class="main_top clearfix">
                    <a class="avatar" href="/v1/user">
                        <img src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                    </a>
                    <div class="title">
                        <a class="name" href="/v1/user">
                            <span>
                                喵星菇凉
                            </span>
                            <i class="iconfont icon-nvsheng1">
                            </i>
                        </a>
                    </div>
                    <div class="info">
                        <ul>
                            <li>
                                <div class="meta_block">
                                    <a href="#">
                                        <p>
                                            200
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
                                            100
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
                                            150
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
                                        356841
                                    </p>
                                    <div>
                                        字数
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="meta_block">
                                    <p>
                                        2420
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
                        <li role="presentation">
                            <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                                <i class="iconfont icon-dongtai1">
                                </i>
                                文章
                            </a>
                        </li>
                        <li class="active" role="presentation">
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
                        <div class="tab-pane" id="wenzhang" role="tabpanel">
                            @include('v1.parts.writing')
                        </div>
                        <div class="tab-pane active" id="dongtai" role="tabpanel">
                            <ul class="list_container">
                                <li>
                                    <div class="personal">
                                        <a class="avatar" href="/v1/user">
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                喵星菇凉
                                            </a>
                                            <a href="#">
                                                <img src="//cdn2.jianshu.io/assets/badges/signed-9702260821906f0d953eab67a29f8e7a2d2e3d20960576347591283a3fbfd867.png"/>
                                            </a>
                                            <small>
                                                关注了专题 · 11.14.16:55
                                            </small>
                                        </div>
                                    </div>
                                    <div class="follow_detail">
                                        <div class="info">
                                            <a class="avatar_collection" href="#">
                                                <img src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                            </a>
                                            <a class="follow" href="#">
                                                <i class="iconfont icon-dagou1">
                                                </i>
                                                <span>
                                                    已关注
                                                </span>
                                            </a>
                                            <a class="title" href="#">
                                                谈谈情，说说爱
                                            </a>
                                            <p>
                                                <a href="#">
                                                    爱你城
                                                </a>
                                                编，70730 篇文章，1117737 人关注
                                            </p>
                                        </div>
                                        <div class="signature">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度认真就好如果你想分享自己或者身边人的爱情故事，欢迎前来投稿
											投稿须知：
											1.本专题仅收录关于爱情的文章。注意：爱情类小说除非足够精彩然后不是连载，不然不在收录范围。另外关于名人伟人的爱情故事也请改投相关专题
											2.第一条中爱情类小说的“精彩”，是指文章有小说的基本结构，运用了基本写法，语言流畅，情节动人。诸如Ａ说Ｂ又说这样毫无人物描写、场景描写的文章就请改投其他相关专题。
											3.请保证文章质量和基本排版，勿出现大量黑体和不相关图片（图片不宜多）
											4.文章字数尽量在1000字以上，当然特别精彩的文章可忽略这个要求
											专题主编 枫小梦 http://www.jianshu.com/u/6aa245e48ccc
											添加主编微信 conan314 进入谈谈情说说爱官方社群。
											关注简书官方微信公众号（jianshuio）,及时阅读简书热门好文
											关注公众号 “简宝玉” （jianshubaoyu），进入简书丰富多彩的专题社群！
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="personal">
                                        <a class="avatar" href="/v1/user">
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                喵星菇凉
                                            </a>
                                            <a href="#">
                                                <img src="//cdn2.jianshu.io/assets/badges/signed-9702260821906f0d953eab67a29f8e7a2d2e3d20960576347591283a3fbfd867.png"/>
                                            </a>
                                            <small>
                                                喜欢了文章 · 11.15.16:12
                                            </small>
                                        </div>
                                    </div>
                                    <div class="substance have_img clearfix">
                                        <div class="conten col-xs-12 col-sm-8 col-md-9">
                                            <a href="/v1/detail">
                                                2018年, SEO行业发展前景, 有几大趋势?
                                            </a>
                                            <p class="article">
                                                我们已经进入2017年第四季度，现在是时候开始考虑未来的一年以及2018年SEO的预期了，我们看到了今年以来最新的搜索行业趋势，到2018年将会更加突出。为了保持领先的地位，我们已经进入2017年第四季度，现在是时候开始考虑未来的一年以及2018年SEO的预期了，我们看到了今年以来最新的搜索行业趋势，到2018年将会更加突出。为了保持领先的地位，我们已经进入2017年第四季度，现在是时候开始考虑未来的一年以及2018年SEO的预期了，我们看到了今年以来最新的搜索行业趋势，到2018年将会更加突出。为了保持领先的地位
                                            </p>
                                        </div>
                                        <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="/v1/detail">
                                            <img src="//upload-images.jianshu.io/upload_images/5713067-789d091267d0b48b.png?imageMogr2/auto-orient/strip|imageView2/1/w/150/h/120"/>
                                        </a>
                                        <div class="statistics col-xs-12">
                                            <a class="origin_author" href="#">
                                                蝙蝠侠IT
                                            </a>
                                            <a href="/v1/detail">
                                                <i class="iconfont icon-yanjing">
                                                </i>
                                                58
                                            </a>
                                            <a href="/v1/detail">
                                                <i class="iconfont icon-weibiaoti5">
                                                </i>
                                                0
                                            </a>
                                            <span>
                                                <i class="iconfont icon-xihuan1">
                                                </i>
                                                1
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="personal">
                                        <a class="avatar" href="/v1/user">
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                喵星菇凉
                                            </a>
                                            <a href="#">
                                                <img src="//cdn2.jianshu.io/assets/badges/signed-9702260821906f0d953eab67a29f8e7a2d2e3d20960576347591283a3fbfd867.png"/>
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
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                喵星菇凉
                                            </a>
                                            <a href="#">
                                                <img src="//cdn2.jianshu.io/assets/badges/signed-9702260821906f0d953eab67a29f8e7a2d2e3d20960576347591283a3fbfd867.png"/>
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
                                                <i class="iconfont icon-dagou1">
                                                </i>
                                                <span>
                                                    已关注
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
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                喵星菇凉
                                            </a>
                                            <a href="#">
                                                <img src="//cdn2.jianshu.io/assets/badges/signed-9702260821906f0d953eab67a29f8e7a2d2e3d20960576347591283a3fbfd867.png"/>
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
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                喵星菇凉
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
                                <li>
                                    <div class="personal">
                                        <a class="avatar" href="/v1/user">
                                            <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/8016539/6c0b408f-6cf6-4fd1-a283-c2446ab63f58.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                                        </a>
                                        <div class="information">
                                            <a href="/v1/user">
                                                喵星菇凉
                                            </a>
                                            <small>
                                                加入了爱你城 · 09.20.13:06
                                            </small>
                                        </div>
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
                            喵星菇凉《好中文的样子》
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
                                我关注的专题/文集
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="iconfont icon-xihuan">
                            </i>
                            <span>
                                我喜欢的文章
                            </span>
                        </a>
                    </li>
                </ul>
                <div>
                    <p class="title">
                        我创建的专题
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
                        <li>
                            <a class="new_collection" href="#">
                                <i class="iconfont icon-jia">
                                </i>
                                <span>
                                    创建一个新专题
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="title">
                        我的文集
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
            </div>
        </div>
    </div>
</div>
@stop

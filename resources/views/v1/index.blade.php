@extends('v1.layouts.blank')

@section('title')
    爱你城 - 最暖心的游戏社交网站
@stop
@section('content')
<div id="v1">
    <div class="index">
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
                            <span>
                                王者荣耀
                            </span>
                        </a>
                        <a class="collection" href="/v1/category">
                            <img src="/logo/col-01.jpg"/>
                            <span>
                                绝地求生
                            </span>
                        </a>
                        <a class="collection" href="/v1/category">
                            <img src="/logo/col-01.jpg"/>
                            <span>
                                剑侠情缘3
                            </span>
                        </a>
                        <a class="collection" href="/v1/category">
                            <img src="/logo/1.jpeg"/>
                            <span>
                                暗恋
                            </span>
                        </a>
                        <a class="collection" href="/v1/category">
                            <img src="/logo/1.jpeg"/>
                            <span>
                                恋爱
                            </span>
                        </a>
                        <a class="collection" href="/v1/category">
                            <img src="/logo/1.jpeg"/>
                            <span>
                                热恋
                            </span>
                        </a>
                        <a class="collection" href="/v1/category">
                            <img src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/240/h/240"/>
                            <span>
                                谈谈情，说说爱
                            </span>
                        </a>
                        <a class="more_hot_collection" href="#">
                            更多热门专题
                            <i class="iconfont icon-gengduo">
                            </i>
                        </a>
                    </div>
                    <ul class="list_container">
                        <li>
                            <div class="personal">
                                <a class="portrait" href="#">
                                    <img alt="头像" src="//upload.jianshu.io/users/upload_avatars/4896574/81748b90-d20d-40fd-a659-127ece846249?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <div class="information">
                                    <a href="#">
                                        空评
                                    </a>
                                    <br/>
                                    <small>
                                        2天前
                                    </small>
                                </div>
                            </div>
                            <div class="substance clearfix">
                                <div class="conten col-xs-12 col-sm-8 col-md-9">
                                    <a href="/v1/detail">
                                        为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？
                                    </a>
                                    <p class="article">
                                        5月17日下午，腾讯控股公布了2017年第一季度财报。财报显示，腾讯一季度营收495.52亿元，同比增长55%；网络游戏收入增长34%至228.11亿元。其中，就智能手机游戏而言，腾讯实现129亿元收入，同比增长57%，此乃受现有及新的游戏如（《王者荣耀》、《穿越火线：枪战王者》及《龙之谷》）所推动。
                                    </p>
                                </div>
                                <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="#">
                                    <img src="//upload-images.jianshu.io/upload_images/4896574-e0f2b6c752dab9c1?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240"/>
                                </a>
                                <div class="statistics col-xs-12">
                                    <a class="special" href="#">
                                        王者荣耀
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        717阅读
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        6评论
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        13喜欢
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="personal">
                                <a class="portrait" href="#">
                                    <img alt="头像" class="img-circle" src="//upload.jianshu.io/users/upload_avatars/1978017/8072afae5460?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <div class="information">
                                    <a href="#">
                                        闫浩
                                    </a>
                                    <br/>
                                    <small>
                                        5天前
                                    </small>
                                </div>
                            </div>
                            <div class="substance clearfix">
                                <div class="conten col-xs-12 col-sm-8 col-md-9">
                                    <a href="/v1/detail">
                                        起风了，谁是下一个王者荣耀？
                                    </a>
                                    <p class="article">
                                        下一个王者荣耀露出苗头很久了，今年夏天开始爆发。过去一年创投界有两个关注的焦点，一个是共享单车、共享充电宝带起来的线下流量入口，另一个是狼人杀和王者荣耀们的游戏社交化浪潮。这两个方向的创业者和巨头们，因缘际会地捕捉到了线上买量不再拥有性价比的历史脉搏，一方选择了开源，将物理投放的线下智能终端作为切入交易的新入口，另一方选择了节流，通过游戏本身的社交属性达成裂变式传播的效果，以较低的成本延长产品的生命周期以及活跃度。
                                    </p>
                                </div>
                                <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="#">
                                    <img src="//upload-images.jianshu.io/upload_images/1978017-b9ea1315e388a415.jpg!1200?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240"/>
                                </a>
                                <div class="statistics col-xs-12">
                                    <a class="special" href="#">
                                        王者荣耀
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        2325阅读
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        5评论
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        61喜欢
                                    </span>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        2赞赏
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="personal">
                                <a class="portrait" href="#">
                                    <img alt="头像" class="img-circle" src="//upload.jianshu.io/users/upload_avatars/1858256/e78657d29b8d.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <div class="information">
                                    <a href="#">
                                        浮云笑此生
                                    </a>
                                    <br/>
                                    <small>
                                        3周前
                                    </small>
                                </div>
                            </div>
                            <div class="substance clearfix">
                                <div class="conten col-xs-12">
                                    <a href="/v1/detail">
                                        相亲与自由恋爱的比较
                                    </a>
                                    <p class="article">
                                        这里的“相亲”主要指的是一种东亚现象。相亲有传统的因素，但它没有遭遇传统普遍的危机，反而是历久弥新。相亲是通过第三方来寻找配偶，第三方可以是父母辈的亲人，可以是同辈的亲友，也可以是中介机构。不管是经由谁介绍，所有相亲的共同特点是：它是一种挑选。由此，相亲也是一种面试，只不过是平等的、双向的面试。这种挑选分为三步进行，第一步是“初试”，这往往是由第三方完成，相亲者并不参与但可能会给第三方提供一些条件；第二步是“复试”，进入复试的往往有多人，此时相亲者互相见面并初步沟通，这也是相亲之所谓相亲的主要方面；第三步是“终试”，往往只有一人进入这个阶段，此时他们往往已经成为实际上的情侣关系，这也是最后考验，直到步入婚姻殿堂为止。
                                    </p>
                                </div>
                                {{--
                                <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="#">
                                    <img src=""/>
                                </a>
                                --}}
                                <div class="statistics col-xs-12">
                                    <a class="special" href="#">
                                        谈谈情，说说爱
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        1795阅读
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        5评论
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        35喜欢
                                    </span>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        7赞赏
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="personal">
                                <a class="portrait" href="#">
                                    <img alt="头像" class="img-circle" src="//upload.jianshu.io/users/upload_avatars/253140/b9adfdadef8a?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <div class="information">
                                    <a href="#">
                                        迎刃
                                    </a>
                                    <br/>
                                    <small>
                                        1个月前
                                    </small>
                                </div>
                            </div>
                            <div class="substance clearfix">
                                <div class="conten col-xs-12 col-sm-8 col-md-9">
                                    <a href="/v1/detail">
                                        如何变得会与异性聊天？
                                    </a>
                                    <p class="article">
                                        与人社交时如何很会聊天是个大家都非常感兴趣的话题，尤其是非常关注与异性聊天的问题。我综合了此前写过的若干篇文字，有了下面的关于聊天的方法论总结。从3个方面入手，让大家看完就能去实践。1，心态篇    2，原因篇    3，策略篇【心态篇】我发现如果没有一个良好的心态作为支撑和前提，做任何事情都会事倍功半。反之，心态好，自信心足，就会有强大的抗挫折承受力，甚至是遇强则强，越挫越勇。
                                    </p>
                                </div>
                                <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="#">
                                    <img src="//upload-images.jianshu.io/upload_images/253140-5c2d51aa0d7c673d.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240"/>
                                </a>
                                <div class="statistics col-xs-12">
                                    <a class="special" href="#">
                                        谈谈情，说说爱
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        317324阅读
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        781评论
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        11029喜欢
                                    </span>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        12赞赏
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="personal">
                                <a class="portrait" href="#">
                                    <img alt="头像" class="img-circle" src="//upload.jianshu.io/users/upload_avatars/6205434/831957b5-a672-4aa2-884b-cf971a8c34d5?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <div class="information">
                                    <a href="#">
                                        网易王三三
                                    </a>
                                    <br/>
                                    <small>
                                        1个月 前
                                    </small>
                                </div>
                            </div>
                            <div class="substance clearfix">
                                <div class="conten col-xs-12 col-sm-8 col-md-9">
                                    <a href="/v1/detail">
                                        吃鸡和生活一样，九分天注定，一分靠打拼
                                    </a>
                                    <p class="article">
                                        这几天，我发现大家的人生追求变了，从“明天不上班”变成了“今晚一定要吃到鸡”。“吃鸡”梗来源于最近火爆全球的一款射击类生存游戏《绝地求生：大逃杀》。主要是讲几十上百个猛男被投放到一个荒岛，为了最终目标——吃鸡而互相厮杀，干掉所有对手活到最后的故事。当然，它还有其他一些流传更广的名字，比如《伏地求生之老阴X大乱斗》《一千零一种死法》《幻影坦克大战千年老苟》等。
                                    </p>
                                </div>
                                <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="#">
                                    <img src="//upload-images.jianshu.io/upload_images/6205434-62e64dc2d81e3625.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240"/>
                                </a>
                                <div class="statistics col-xs-12">
                                    <a class="special" href="#">
                                        绝地求生
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        834阅读
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        6评论
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        27喜欢
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="personal">
                                <a class="portrait" href="#">
                                    <img alt="头像" class="img-circle" src="//upload.jianshu.io/users/upload_avatars/6486956/8b5f880e-f7ba-446b-8b59-1532e087fe3e.jpeg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <div class="information">
                                    <a href="#">
                                        智_先生
                                    </a>
                                    <br/>
                                    <small>
                                        2个月 前
                                    </small>
                                </div>
                            </div>
                            <div class="substance clearfix">
                                <div class="conten col-xs-12 col-sm-8 col-md-9">
                                    <a href="/v1/detail">
                                        我们该感谢王者荣耀耗尽了80%的人上升的空间
                                    </a>
                                    <p class="article">
                                        在当下，以《王者荣耀》、《阴阳师》为首的一批手游正逐渐霸占人们的手机屏幕，以此而衍生的电竞、直播以及代练行业风光无限，潜力巨大。最近，为保障未成年人健康成长，腾讯经过将近一个月的调试和内测后，将于7月4日以《王者荣耀》为试点，率先推出健康游戏防沉迷系统的“三板斧”，对未成年人的游戏时间作出限制：12岁以下每天限玩一小时。（小学生听了绝不会流泪，并笑着拿出奶奶的身份证。）当前国内还没有移动游戏防沉迷的明确规定，《王者荣耀》在顶着“亡者毒药”的社会舆论中做出了实际行动，这是一种自保行为，也可以说是社会责任感的体现。但事实上，《王者荣耀》的最大玩家群体当属大学生和中年人群体，第三方数据分析机构talkingData统计的数据显示，王者荣耀的用户群中，大学生占21.8%，中小学生占2.7%；上班族占68.7%。
                                    </p>
                                </div>
                                <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="#">
                                    <img src="//upload-images.jianshu.io/upload_images/6486956-1ce7c76170f7b916.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240"/>
                                </a>
                                <div class="statistics col-xs-12">
                                    <a class="special" href="#">
                                        王者荣耀
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        113787阅读
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        1096评论
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        3222喜欢
                                    </span>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        49赞赏
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="personal">
                                <a class="portrait" href="#">
                                    <img alt="头像" class="img-circle" src="//upload.jianshu.io/users/upload_avatars/1408329/1ed19a008804?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <div class="information">
                                    <a href="#">
                                        冷清持
                                    </a>
                                    <br/>
                                    <small>
                                        2个月 前
                                    </small>
                                </div>
                            </div>
                            <div class="substance clearfix">
                                <div class="conten col-xs-12 col-sm-8 col-md-9">
                                    <a href="/v1/detail">
                                        和男朋友一起玩游戏是一种怎样的感觉
                                    </a>
                                    <p class="article">
                                        搬新家了，别人都是在考虑装修，买家具，整理房间，而作为一个殿堂级游戏迷，我和男朋友却一致把最大重心放在了买电脑上。区别是我以前喜欢玩单机，男朋友喜欢玩网游。﻿结果当然是买了一模一样的两台高配。﻿某天我们躺在沙发上，他突然说，哎宝宝，不如我带你玩个游戏吧。﻿我弯眉:“好啊好啊是你拖地我加油的游戏吗。”﻿他无视:“一起玩游戏很棒的呀，可以建个小号慢慢升级啊，一起打副本啊，一起钓鱼啊，看风景啦…是不是感觉超棒？我朋友也在玩我们可以组个固定队！”﻿
                                    </p>
                                </div>
                                <a class="wrap_img col-xs-10 col-sm-4 col-md-3 col-xs-offset-1 col-sm-offset-0" href="#">
                                    <img src="http://upload-images.jianshu.io/upload_images/1408329-d38d06f1f04211fd.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1080/q/100"/>
                                </a>
                                <div class="statistics col-xs-12">
                                    <a class="special" href="#">
                                        谈谈情，说说爱
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        11898阅读
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <a href="#">
                                        299评论
                                    </a>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        303喜欢
                                    </span>
                                    <em>
                                        ·
                                    </em>
                                    <span>
                                        5赞赏
                                    </span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a class="load_more" href="#">
                        阅读更多
                    </a>
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
                        <a class="sign col-xs-12" href="#">
                            <span>
                                每日一签
                                <i class="iconfont icon-gengduo">
                                </i>
                            </span>
                        </a>
                        <a class="newlist col-xs-12" href="#">
                            <span>
                                新上榜
                                <i class="iconfont icon-gengduo">
                                </i>
                            </span>
                        </a>
                        <a class="game col-xs-12" href="#">
                            <span>
                                游戏热门
                                <i class="iconfont icon-gengduo">
                                </i>
                            </span>
                        </a>
                        <a class="love col-xs-12" href="#">
                            <span>
                                恋爱热门
                                <i class="iconfont icon-gengduo">
                                </i>
                            </span>
                        </a>
                    </div>
                    <div class="codes clearfix">
                        <a class="col-xs-12" href="#">
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
                            <a href="#">
                                查看更多
                            </a>
                        </div>
                        <a class="note" href="#">
                            <img src="http://upload-images.jianshu.io/upload_images/1714520-119e82e1662d86ac.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240"/>
                            <div class="note_title">
                                恋爱要谈到什么程度，才适合结婚呢？
                            </div>
                        </a>
                        <a class="note" href="#">
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
                            <a href="#">
                                <i class="glyphicon glyphicon-refresh">
                                </i>
                                换一批
                            </a>
                        </div>
                        <ul class="list">
                            <li>
                                <a class="avatar" href="#">
                                    <img src="//upload.jianshu.io/users/upload_avatars/19107/08f8146dae87.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="follow" href="#">
                                    <i class="glyphicon glyphicon-plus">
                                    </i>
                                    关注
                                </a>
                                <a class="name" href="#">
                                    王佩
                                </a>
                                <p>
                                    写了400.9k字 · 13.8k喜欢
                                </p>
                            </li>
                            <li>
                                <a class="avatar" href="#">
                                    <img src="//upload.jianshu.io/users/upload_avatars/6287/06c537002583.png?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="follow" href="#">
                                    <i class="glyphicon glyphicon-plus">
                                    </i>
                                    关注
                                </a>
                                <a class="name" href="#">
                                    刘淼
                                </a>
                                <p>
                                    写了375.5k字 · 20.5k喜欢
                                </p>
                            </li>
                            <li>
                                <a class="avatar" href="#">
                                    <img src="//upload.jianshu.io/users/upload_avatars/1996705/738ba2908445?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="follow" href="#">
                                    <i class="glyphicon glyphicon-plus">
                                    </i>
                                    关注
                                </a>
                                <a class="name" href="#">
                                    白发老籣
                                </a>
                                <p>
                                    写了50.5k字 · 5.7k喜欢
                                </p>
                            </li>
                            <li>
                                <a class="avatar" href="#">
                                    <img src="//upload.jianshu.io/users/upload_avatars/6198903/a70dc654-6674-4b71-925f-0389f31fb095.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="follow" href="#">
                                    <i class="glyphicon glyphicon-plus">
                                    </i>
                                    关注
                                </a>
                                <a class="name" href="#">
                                    魏童
                                </a>
                                <p>
                                    写了39.4k字 · 1.4k喜欢
                                </p>
                            </li>
                            <li>
                                <a class="avatar" href="#">
                                    <img src="//upload.jianshu.io/users/upload_avatars/7663825/7c28763e-002b-4e89-8dea-5b8da210ef2c.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/96/h/96"/>
                                </a>
                                <a class="follow" href="#">
                                    <i class="glyphicon glyphicon-plus">
                                    </i>
                                    关注
                                </a>
                                <a class="name" href="#">
                                    名贵的考拉熊
                                </a>
                                <p>
                                    写了104.3k字 · 8.3k喜欢
                                </p>
                            </li>
                        </ul>
                        <a class="find_more" href="#">
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
                            <a href="#">
                                查看更多
                            </a>
                        </div>
                        <a class="videos_list" href="#">
                            <div class="screenshot">
                                <img src="https://ainicheng.com/storage/img/1806.jpeg"/>
                            </div>
                            <div class="list_title">
                                王者荣耀打野必备攻略 5v5野区地图分布详解
                            </div>
                        </a>
                        <a class="videos_list" href="#">
                            <div class="screenshot">
                                <img src="https://ainicheng.com/storage/img/1890.png"/>
                            </div>
                            <div class="list_title">
                                王者荣耀最强奶妈蔡文姬怎么玩2.0加强版
                            </div>
                        </a>
                        <a class="videos_list" href="#">
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
</div>
@stop

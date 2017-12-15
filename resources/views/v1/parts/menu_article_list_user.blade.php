{{-- 用户页文章的标签页 --}}
<div>
    <!-- Nav tabs -->
    <ul class="trigger_menu" role="tablist">
        <li class="active" role="presentation">
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
            @include('v1.parts.article_list_nocategory')
        </div>
        <div class="tab-pane fade" id="dongtai" role="tabpanel">
            <ul class="article_list">
                <li class="article_item have_img">
                    <a class="wrap_img" href="/v1/detail" target="_blank">
                        <img src="/images/details_07.jpg"/>
                    </a>
                    <div class="content">
                        <div class="author">
                            <a class="avatar" href="/v1/user" target="_blank">
                                <img src="/images/photo_02.jpg"/>
                            </a>
                            <div class="info">
                                <a href="/v1/user" target="_blank">
                                    空评
                                </a>
                                <a href="/v1/detail" target="_blank">
                                    <img src="/images/vip1.png"/>
                                </a>
                                <span class="time">
                                    2天前
                                </span>
                            </div>
                        </div>
                        <a class="title" href="/v1/detail" target="_blank">
                            魔兽世界7.2.5全新版本资料片：新橙戒+新黑庙+乐队活动
                        </a>
                        <p class="abstract">
                            《魔兽世界》7.2.5版本今日在国服上线。虽然是小版本更新，但改动内容还是十分丰富的。比如新的橙装、大秘境调整、黑庙加入时空漫游、克罗米战役等等。以下是17173为各位整理的《魔兽世界》7.2.5版本今日在国服上线。虽然是小版本更新，但改动内容还是十分丰富的。比如新的橙装、大秘境调整、黑庙加入时空漫游、克罗米战役等等。以下是17173为各位整理的
                        </p>
                        <div class="meta">
                            <a href="/v1/detail" target="_blank">
                                <i class="iconfont icon-liulan">
                                </i>
                                717
                            </a>
                            <a href="/v1/detail" target="_blank">
                                <i class="iconfont icon-svg37">
                                </i>
                                6
                            </a>
                            <span>
                                <i class="iconfont icon-03xihuan">
                                </i>
                                13
                            </span>
                        </div>
                    </div>
                </li>
                <li class="article_item">
                    <div class="author">
                        <a class="avatar" href="/v1/user" target="_blank">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <div class="info">
                            <a href="/v1/user" target="_blank">
                                空评
                            </a>
                            <span>
                                关注了作者 · 06.14.23:55
                            </span>
                        </div>
                    </div>
                    <div class="follow_detail">
                        <div class="info">
                            <a class="avatar" href="/v1/user" target="_blank">
                                <img src="/images/photo_03.jpg"/>
                            </a>
                            <a class="follow" href="javascript:;">
                                <span>
                                    ＋ 关注
                                </span>
                            </a>
                            <a class="title" href="/v1/user" target="_blank">
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
                <li class="article_item">
                    <div class="author">
                        <a class="avatar" href="/v1/user" target="_blank">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <div class="info">
                            <a href="/v1/user" target="_blank">
                                空评
                            </a>
                            <span>
                                发表了评论 · 10.30.13:26
                            </span>
                        </div>
                    </div>
                    <div class="comment">
                        <p>
                            <a href="/v1/user" target="_blank">
                                @夜_2d81
                            </a>
                            丧尸从0到1%，这个过程，军队就开始介入了，基本不可能达到99%
                        </p>
                        <blockquote>
                            <a class="title" href="/v1/detail" target="_blank">
                                末日来临，最正确的丧尸自救指南
                            </a>
                            <p class="abstract">
                                这是智先生的第8篇原创文章 当末日来临 你准备好了吗？ 最近我看完了马克斯·布鲁克斯写的《僵尸生存指南》，书本教会了人们如何在丧尸横行的世界中生存，并提供了从选择武器到制定逃...
                            </p>
                            <div class="meta">
                                <div class="origin_author">
                                    <a href="/v1/user" target="_blank">
                                        空评
                                    </a>
                                </div>
                                <a href="/v1/detail" target="_blank">
                                    <i class="iconfont icon-liulan">
                                    </i>
                                    1649
                                </a>
                                <a href="/v1/detail" target="_blank">
                                    <i class="iconfont icon-svg37">
                                    </i>
                                    22
                                </a>
                                <span>
                                    <i class="iconfont icon-03xihuan">
                                    </i>
                                    72
                                </span>
                            </div>
                        </blockquote>
                    </div>
                </li>
                <li class="article_item">
                    <div class="author">
                        <a class="avatar" href="/v1/user" target="_blank">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <div class="info">
                            <a href="/v1/user" target="_blank">
                                空评
                            </a>
                            <em>
                                ·
                            </em>
                            <span>
                                赞了评论 · 10.30.13:26
                            </span>
                        </div>
                    </div>
                    <div class="comment">
                        <p>
                            为这篇文章注册简书，我也挺难受大学寝室的环境的，本身自己挺孤僻，习惯一个人待着，最不能忍的是大二的洗澡是十几个开放式没有门的洗澡间···庆幸室友的性格都挺好，现在毕业两年还在联系，今天还和其中一个说想给她设计婚礼请帖的图案的，我玩的很好的高中同学大学5年的室友则是比较针对她，好像是因为拿奖学金之类的事吧，然后处处挤兑，她研究生的室友则是家教啥的都很好，她现在可开心了 我也很替她开心，她读研和我工作在一个城市
                        </p>
                        <blockquote>
                            <div class="meta">
                                <div class="origin_author">
                                    <a href="/v1/user" target="_blank">
                                        郭璐Alu
                                    </a>
                                    <span>
                                        评论自
                                        <a href="/v1/detail" target="_blank">
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
        <div class="tab-pane fade" id="pinglun" role="tabpanel">
            @include('v1.parts.article_list_nocategory')
        </div>
        <div class="tab-pane fade" id="huo" role="tabpanel">
            @include('v1.parts.article_list_nocategory')
        </div>
    </div>
</div>
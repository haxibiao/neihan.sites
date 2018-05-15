{{-- 文章动态的标签页 --}}
<div>
    <!-- Nav tabs -->
    <ul class="trigger_menu" role="tablist">
        <li role="presentation">
            <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                <i class="iconfont icon-wenji">
                </i>
                文章
            </a>
        </li>
        <li class="active" role="presentation">
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
        <div class="tab-pane fade" id="wenzhang" role="tabpanel">
            @include('v2.parts.article.article_list_nocategory')
        </div>
        <div class="tab-pane fade in active" id="dongtai" role="tabpanel">
            <ul class="article_list">
                <li class="article_item">
                    <div class="author">
                        <a class="avatar" href="/v2/user" target="_blank">
                            <img src="/images/photo_01.jpg"/>
                        </a>
                        <div class="info_meta">
                            <a href="/v2/user" target="_blank" class="nickname">
                                喵星菇凉
                            </a>
                            <span class="time">
                                关注了专题 · 11.14.16:55
                            </span>
                        </div>
                    </div>
                    <div class="follow_detail">
                        <div class="author">
                            <a class="avatar avatar_sm avatar_collection" href="/v2/category/2" target="_blank">
                                <img src="/images/category_02.jpg"/>
                            </a>
                            <a class="btn_base btn_followed" href="javascript:;">
                                <span>
                                    <i class="iconfont icon-weibiaoti12">
                                    </i>
                                    <i class="iconfont icon-cha">
                                    </i>
                                </span>
                            </a>
                            <div class="info_meta">
                                <a class="nickname" href="/v2/category/2" target="_blank">
                                    谈谈情，说说爱
                                </a>
                                <p class="meta">
                                    <a href="/v2/user" target="_blank">
                                        爱你城
                                    </a>
                                    编，70730 篇文章，1117737 人关注
                                </p>
                            </div>
                        </div>
                        <div class="signature">
                            <span>
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
                            </span>
                        </div>
                    </div>
                </li>
                <li class="article_item have_img">
                    <a class="wrap_img" href="/v2/detail" target="_blank">
                        <img src="/images/details_10.png"/>
                    </a>
                    <div class="content">
                        <div class="author">
                            <a class="avatar" href="/v2/user" target="_blank">
                                <img src="/images/photo_01.jpg"/>
                            </a>
                            <div class="info_meta">
                                <a href="/v2/user" target="_blank" class="nickname">
                                    喵星菇凉
                                </a>
                                <a href="/v2/detail" target="_blank">
                                    <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_xs"/>
                                </a>
                                <span class="time">
                                    喜欢了文章 · 11.15.16:12
                                </span>
                            </div>
                        </div>
                        <a class="headline paper_title" href="/v2/detail" target="_blank">
                            <span>2018年, SEO行业发展前景, 有几大趋势?</span>
                        </a>
                        <p class="abstract">
                            我们已经进入2017年第四季度，现在是时候开始考虑未来的一年以及2018年SEO的预期了，我们看到了今年以来最新的搜索行业趋势，到2018年将会更加突出。为了保持领先的地位，我们已经进入2017年第四季度，现在是时候开始考虑未来的一年以及2018年SEO的预期了，我们看到了今年以来最新的搜索行业趋势，到2018年将会更加突出。为了保持领先的地位，我们已经进入2017年第四季度，现在是时候开始考虑未来的一年以及2018年SEO的预期了，我们看到了今年以来最新的搜索行业趋势，到2018年将会更加突出。为了保持领先的地位
                        </p>
                        <div class="meta">
                            <div class="origin_author">
                                <a href="/v2/user" target="_blank">
                                    蝙蝠侠IT
                                </a>
                            </div>
                            <a href="/v2/detail" target="_blank" class="count count_link">
                                <i class="iconfont icon-liulan">
                                </i>
                                58
                            </a>
                            <a href="/v2/detail" target="_blank" class="count count_link">
                                <i class="iconfont icon-svg37">
                                </i>
                                0
                            </a>
                            <span class="count">
                                <i class="iconfont icon-03xihuan">
                                </i>
                                1
                            </span>
                        </div>
                    </div>
                </li>
                <li class="article_item have_img">
                    <a class="wrap_img" href="/v2/detail" target="_blank">
                        <img src="/images/details_07.jpeg"/>
                    </a>
                    <div class="content">
                        <div class="author">
                            <a class="avatar" href="/v2/user" target="_blank">
                                <img src="/images/photo_01.jpg"/>
                            </a>
                            <div class="info_meta">
                                <a href="/v2/user" target="_blank" class="nickname">
                                    喵星菇凉
                                </a>
                                <a href="/v2/detail" target="_blank">
                                    <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_xs"/>
                                </a>
                                <span class="time">
                                    发表了文章 · 06.15.12:12
                                </span>
                            </div>
                        </div>
                        <a class="headline paper_title" href="/v2/detail" target="_blank">
                            <span>魔兽世界7.2.5全新版本资料片：新橙戒+新黑庙+乐队活动</span>
                        </a>
                        <p class="abstract">
                            《魔兽世界》7.2.5版本今日在国服上线。虽然是小版本更新，但改动内容还是十分丰富的。比如新的橙装、大秘境调整、黑庙加入时空漫游、克罗米战役等等。以下是17173为各位整理的《魔兽世界》7.2.5版本今日在国服上线。虽然是小版本更新，但改动内容还是十分丰富的。比如新的橙装、大秘境调整、黑庙加入时空漫游、克罗米战役等等。以下是17173为各位整理的
                        </p>
                        <div class="meta">
                            <a href="/v2/detail" target="_blank" class="count count_link">
                                <i class="iconfont icon-liulan">
                                </i>
                                717
                            </a>
                            <a href="/v2/detail" target="_blank" class="count count_link">
                                <i class="iconfont icon-svg37">
                                </i>
                                6
                            </a>
                            <span class="count">
                                <i class="iconfont icon-03xihuan">
                                </i>
                                13
                            </span>
                        </div>
                    </div>
                </li>
                <li class="article_item">
                    <div class="author">
                        <a class="avatar" href="/v2/user" target="_blank">
                            <img src="/images/photo_01.jpg"/>
                        </a>
                        <div class="info_meta">
                            <a href="/v2/user" target="_blank" class="nickname">
                                喵星菇凉
                            </a>
                            <span class="time">
                                关注了作者 · 06.14.23:55
                            </span>
                        </div>
                    </div>
                    <div class="follow_detail">
                        <div class="author">
                            <a class="avatar avatar_sm" href="/v2/user" target="_blank">
                                <img src="/images/photo_03.jpg"/>
                            </a>
                            <a class="btn_base btn_followed" href="javascript:;">
                                <span>
                                    <i class="iconfont icon-weibiaoti12">
                                    </i>
                                    <i class="iconfont icon-cha">
                                    </i>
                                </span>
                            </a>
                            <div class="info_meta">
                                <a class="nickname" href="/v2/user" target="_blank">
                                    Dreamover1010
                                </a>
                                <p class="meta">
                                    写了 16299 字，被 90 人关注，获得了 140 个喜欢
                                </p>
                            </div>
                        </div>
                        <div class="signature">
                            <span>
                                在广告行业摸爬滚打几年的设计师逃出升天
                            </span>
                        </div>
                    </div>
                </li>
                <li class="article_item">
                    <div class="author">
                        <a class="avatar" href="/v2/user" target="_blank">
                            <img src="/images/photo_01.jpg"/>
                        </a>
                        <div class="info_meta">
                            <a href="/v2/user" target="_blank" class="nickname">
                                喵星菇凉
                            </a>
                            <span class="time">
                                发表了评论 · 10.30.13:26
                            </span>
                        </div>
                    </div>
                    <div class="comment_dynamic">
                        <p class="discuss">
                            <a href="/v2/user" target="_blank">
                                @夜_2d81
                            </a>
                            丧尸从0到1%，这个过程，军队就开始介入了，基本不可能达到99%
                        </p>
                        <blockquote>
                            <a class="headline paper_title" href="/v2/detail" target="_blank">
                                <span>末日来临，最正确的丧尸自救指南</span>
                            </a>
                            <p class="abstract">
                                这是智先生的第8篇原创文章 当末日来临 你准备好了吗？ 最近我看完了马克斯·布鲁克斯写的《僵尸生存指南》，书本教会了人们如何在丧尸横行的世界中生存，并提供了从选择武器到制定逃...
                            </p>
                            <div class="meta">
                                <div class="origin_author">
                                    <a href="/v2/user" target="_blank">
                                        空评
                                    </a>
                                </div>
                                <a href="/v2/detail" target="_blank" class="count count_link">
                                    <i class="iconfont icon-liulan">
                                    </i>
                                    1649
                                </a>
                                <a href="/v2/detail" target="_blank" class="count count_link">
                                    <i class="iconfont icon-svg37">
                                    </i>
                                    22
                                </a>
                                <span class="count">
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
                        <a class="avatar" href="/v2/user" target="_blank">
                            <img src="/images/photo_01.jpg"/>
                        </a>
                        <div class="info_meta">
                            <a href="/v2/user" target="_blank" class="nickname">
                                喵星菇凉
                            </a>
                            <span class="time">
                                赞了评论 · 10.30.13:26
                            </span>
                        </div>
                    </div>
                    <div class="comment_dynamic">
                        <p class="discuss">
                            为这篇文章注册简书，我也挺难受大学寝室的环境的，本身自己挺孤僻，习惯一个人待着，最不能忍的是大二的洗澡是十几个开放式没有门的洗澡间···庆幸室友的性格都挺好，现在毕业两年还在联系，今天还和其中一个说想给她设计婚礼请帖的图案的，我玩的很好的高中同学大学5年的室友则是比较针对她，好像是因为拿奖学金之类的事吧，然后处处挤兑，她研究生的室友则是家教啥的都很好，她现在可开心了 我也很替她开心，她读研和我工作在一个城市
                        </p>
                        <blockquote>
                            <div class="meta">
                                <div class="origin_author">
                                    <a href="/v2/user" target="_blank">
                                        郭璐Alu
                                    </a>
                                    <span>
                                        评论自
                                        <a href="/v2/detail" target="_blank">
                                            集体宿舍，将3000万大学生拉入深渊
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </blockquote>
                    </div>
                </li>
                <li class="article_item">
                    <div class="author">
                        <a class="avatar" href="/v2/user" target="_blank">
                            <img src="/images/photo_01.jpg"/>
                        </a>
                        <div class="info_meta">
                            <a href="/v2/user" target="_blank" class="nickname">
                                喵星菇凉
                            </a>
                            <span class="time">
                                加入了爱你城 · 09.20.13:06
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="tab-pane fade" id="pinglun" role="tabpanel">
            @include('v2.parts.article.article_list_nocategory')
        </div>
        <div class="tab-pane fade" id="huo" role="tabpanel">
            @include('v2.parts.article.article_list_nocategory')
        </div>
    </div>
</div>
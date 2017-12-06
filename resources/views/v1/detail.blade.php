@extends('v1.layouts.app')

@section('title')
    为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？ - 爱你城
@stop
@section('content')
<div id="detail">
    <div class="note">
        <div class="container">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="article">
                    <h1 class="title">
                        为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？
                    </h1>
                    <div class="author">
                        <a class="avatar" href="#">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <div class="info">
                            <span class="name">
                                <a href="#">
                                    空评
                                </a>
                            </span>
                            <a class="follow" href="#">
                                <span>
                                    ＋ 关注
                                </span>
                            </a>
                            <div class="meta">
                                <span>
                                    2017.06.15 12:09
                                </span>
                                <span>
                                    字数 5929
                                </span>
                                <span>
                                    阅读 761
                                </span>
                                <span>
                                    评论 6
                                </span>
                                <span>
                                    喜欢 13
                                </span>
                            </div>
                        </div>
                    </div>
                    @include('v1.parts.article_body')
                    <div class="article_foot">
                        <a class="notebook" href="#">
                            <i class="iconfont icon-wenji">
                            </i>
                            <span>
                                日记本
                            </span>
                        </a>
                        <div class="copyright">
                            © 著作权归作者所有
                        </div>
                        <div class="modal_wrap">
                            <a href="#">
                                举报文章
                            </a>
                        </div>
                    </div>
                </div>
                <div class="follow_detail">
                    <div class="info">
                        <a class="avatar" href="/v1/user" target="_blank">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <a class="follow" href="javascript:;">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                        <a class="title" href="/v1/user" target="_blank">
                            空评
                        </a>
                        <p>
                            写了 17299 字，被 6 人关注，获得了 16 个喜欢
                        </p>
                    </div>
                    <div class="signature">
                        一个专注分享游戏文化的地方，一个专门原创app体验的地方
                    </div>
                </div>
                <div class="support_author">
                    <p>
                        如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！
                    </p>
                    <div class="btn_pay">
                        赞赏支持
                    </div>
                    <div class="supporter">
                        <ul class="support_list">
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
                        </ul>
                        <span class="rewad_user">
                            等10人
                        </span>
                    </div>
                </div>
                <div class="meta_bottom">
                    <div class="like">
                        <div class="like_group">
                            <div class="btn_like">
                                <a href="#">
                                    <i class="iconfont icon-xin">
                                    </i>
                                    喜欢
                                </a>
                            </div>
                            <div class="modal_wrap">
                                <a href="#">
                                    13
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="share_group">
                        <a class="share_circle" href="#">
                            <i class="iconfont icon-weixin1">
                            </i>
                        </a>
                        <a class="share_circle" href="#">
                            <i class="iconfont icon-sina">
                            </i>
                        </a>
                        <a class="share_circle" href="#">
                            <i class="iconfont icon-zhaopian">
                            </i>
                        </a>
                        <a class="share_circle more_share" href="#">
                            更多分享
                        </a>
                    </div>
                </div>
                <div>
                    <div class="comment_list">
                        <new-comment>
                        </new-comment>
                        <div class="normal_comment_list">
                            <div>
                                <div>
                                    <div class="top">
                                        <span>
                                            6条评论
                                        </span>
                                        <a class="author_only" href="#">
                                            只看作者
                                        </a>
                                        <div class="pull-right">
                                            <a class="active" href="#">
                                                按喜欢排序
                                            </a>
                                            <a href="#">
                                                按时间正序
                                            </a>
                                            <a href="#">
                                                按时间倒序
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="comment">
                                    <div>
                                        <div class="author">
                                            <a class="avatar" href="#">
                                                <img src="/images/photo_03.jpg"/>
                                            </a>
                                            <div class="info">
                                                <a class="name" href="#">
                                                    LyonHunter
                                                </a>
                                                <div class="meta">
                                                    <span>
                                                        5楼 · 2017.08.12 23:06
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment_wrap">
                                            <p>
                                                (2015年10月入坑，且已到贵族五，假设下所有人都充到了贵族五的话。。。)
                                            </p>
                                            <div class="tool_group">
                                                <a href="#">
                                                    <i class="iconfont icon-fabulous">
                                                    </i>
                                                    <span>
                                                        赞
                                                    </span>
                                                </a>
                                                <a href="#">
                                                    <i class="iconfont icon-xinxi">
                                                    </i>
                                                    <span>
                                                        回复
                                                    </span>
                                                </a>
                                                <a class="report" href="#">
                                                    <span>
                                                        举报
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="comment">
                                    <div>
                                        <div class="author">
                                            <a class="avatar" href="#">
                                                <img src="/images/photo_03.jpg"/>
                                            </a>
                                            <div class="info">
                                                <a class="name" href="#">
                                                    呦小君
                                                </a>
                                                <div class="meta">
                                                    <span>
                                                        7楼 · 2017.07.05 07:47
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment_wrap">
                                            <p>
                                                可是我觉得周围的精英很少有玩这个的，至少我的研究生同学没有…平庸的始终平庸，没有农药还有假药
                                            </p>
                                            <div class="tool_group">
                                                <a href="#">
                                                    <i class="iconfont icon-fabulous">
                                                    </i>
                                                    <span>
                                                        237人赞
                                                    </span>
                                                </a>
                                                <a href="#">
                                                    <i class="iconfont icon-xinxi">
                                                    </i>
                                                    <span>
                                                        回复
                                                    </span>
                                                </a>
                                                <a class="report" href="#">
                                                    <span>
                                                        举报
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sub_comment_list">
                                        <div class="sub_comment">
                                            <p>
                                                <a href="#">
                                                    智_先生
                                                </a>
                                                ：
                                                <span>
                                                    物以类聚，人以群分，不同的圈子文化对一个人的影响是比较大
                                                </span>
                                            </p>
                                            <div class="sub_tool_group">
                                                <span>
                                                    2017.07.05 08:45
                                                </span>
                                                <a href="#">
                                                    <i class="iconfont icon-xinxi">
                                                    </i>
                                                    <span>
                                                        回复
                                                    </span>
                                                </a>
                                                <a class="report" href="#">
                                                    <span>
                                                        举报
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="sub_comment">
                                            <p>
                                                <a href="#">
                                                    心若冰清_
                                                </a>
                                                ：
                                                <span>
                                                    这个写的确实很真实
                                                </span>
                                            </p>
                                            <div class="sub_tool_group">
                                                <span>
                                                    2017.07.05 08:45
                                                </span>
                                                <a href="#">
                                                    <i class="iconfont icon-xinxi">
                                                    </i>
                                                    <span>
                                                        回复
                                                    </span>
                                                </a>
                                                <a class="report" href="#">
                                                    <span>
                                                        举报
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="sub_comment">
                                            <p>
                                                <a href="#">
                                                    吕岳阳
                                                </a>
                                                ：
                                                <span>
                                                    我很费解啊，竟然有东西比撸代码还有意思。
                                                </span>
                                            </p>
                                            <div class="sub_tool_group">
                                                <span>
                                                    2017.07.05 08:45
                                                </span>
                                                <a href="#">
                                                    <i class="iconfont icon-xinxi">
                                                    </i>
                                                    <span>
                                                        回复
                                                    </span>
                                                </a>
                                                <a class="report" href="#">
                                                    <span>
                                                        举报
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="sub_comment more_comment">
                                            <a class="add_comment_btn" href="#">
                                                <i class="iconfont icon-xie">
                                                </i>
                                                <span>
                                                    添加新评论
                                                </span>
                                            </a>
                                            <span class="line_warp">
                                                还有67条评论，
                                                <a href="#">
                                                    展开查看
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="pagination">
                            <li>
                                <a href="#">
                                    <span>
                                        上一页
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    1
                                </a>
                            </li>
                            <li>
                                <a class="active" href="#">
                                    2
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    3
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span>
                                        下一页
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="note_bottom">
        <div class="container">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div>
                    <div class="main">
                        <div class="title">
                            被以下专题收入，发现更多相似内容
                        </div>
                        <div class="include_collection">
                            <a class="item" href="#">
                                <div class="name">
                                    ＋ 收入我的主题
                                </div>
                            </a>
                            <a class="item" href="#">
                                <img src="/images/category_08.jpg"/>
                                <div class="name">
                                    剑侠情缘
                                </div>
                            </a>
                            <a class="item" href="#">
                                <img src="/images/category_01.jpeg"/>
                                <div class="name">
                                    王者荣耀
                                </div>
                            </a>
                            <a class="item show_more" href="#">
                                <div class="name">
                                    展开更多
                                    <i class="iconfont icon-xia">
                                    </i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="recommend_note">
                        <div class="meta">
                            <div class="title">
                                推荐阅读
                                <a href="#">
                                    更多精彩内容
                                    <i class="iconfont icon-youbian">
                                    </i>
                                </a>
                            </div>
                        </div>
                        @include('v1.parts.article_list_recommend')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

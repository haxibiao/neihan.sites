@extends('v2.layouts.app')

@section('title')
    为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？ - 爱你城
@stop
@section('content')
<div id="detail">
    <div class="note">
        <div class="container">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="article">
                    <h1 class="headline">
                        为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？
                    </h1>
                    <div class="author">
                        <a class="avatar avatar_sm" href="/v2/home" target="_blank">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <div class="info_meta">
                            <a href="/v2/home" class="nickname">
                                空评
                            </a>
                            <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_sm" />
                            <a class="btn_base btn_follow btn_follow_xs" href="javascript:;">
                                <span>
                                    ＋ 关注
                                </span>
                            </a>
                            <a href="javascript:;" target="_blank" class="btn_base btn_edit pull-right">编辑文章</a>
                            <div class="meta">
                                <span data-toggle="tooltip" data-placement="bottom" title="最后编辑于 2017.06.16.10:14">
                                    2017.06.15 12:09*
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
                    @include('v2.parts.article.article_body')
                    <div class="article_foot">
                        <a class="notebook" href="#">
                            <i class="iconfont icon-wenji">
                            </i>
                            <span>
                                日记本
                            </span>
                        </a>
                        <div class="copyright" data-original-title="转载请联系作者获得授权，并标注“爱你城作者”。" data-toggle="tooltip">
                            © 著作权归作者所有
                        </div>
                        {{-- <div class="modal_wrap">
                            <a href="#">
                                举报文章
                            </a>
                        </div> --}}
                    </div>
                </div>
                <div class="follow_detail">
                    <div class="author">
                        <a class="avatar avatar_sm" href="/v2/user" target="_blank">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <a class="btn_base btn_follow" href="javascript:;">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                        <div class="info_meta">
                            <a class="nickname" href="/v2/user" target="_blank">
                                空评
                            </a>
                            <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_sm" />
                            <div class="meta">
                                写了 17299 字，被 6 人关注，获得了 16 个喜欢
                            </div>
                        </div>
                    </div>
                    <div class="signature">
                        <span>一个专注分享游戏文化的地方，一个专门原创app体验的地方</span>
                    </div>
                </div>
                <div class="support_author">
                    <p>
                        如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！
                    </p>
                    <div class="btn_base btn_pay">
                        赞赏支持
                    </div>
                    <div>
                        <ul class="collection_follower">
                            <li>
                                <a class="avatar avatar_xs" href="/v2/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar avatar_xs" href="/v2/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar avatar_xs" href="/v2/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar avatar_xs" href="/v2/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar avatar_xs" href="/v2/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar avatar_xs" href="/v2/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar avatar_xs" href="/v2/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <span class="rewad_user">
                                等10人
                            </span>
                        </ul>

                    </div>
                </div>
                <div class="meta_bottom">
                    <div class="like">
                        <div class="btn_base btn_like_group">
                            <div class="btn_like">
                                <a href="#">
                                    <i class="iconfont icon-xin">
                                    </i>
                                    {{-- <i class="iconfont icon-03xihuan">
                                    </i> --}}
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
                        <a class="share_circle" href="#" data-placement="top" data-container="body" data-toggle="tooltip" data-trigger="hover" data-original-title="分享到微信">
                            <i class="iconfont icon-weixin1">
                            </i>
                        </a>
                        <a class="share_circle" href="#" data-placement="top" data-container="body" data-toggle="tooltip" data-trigger="hover" data-original-title="分享到微博">
                            <i class="iconfont icon-sina">
                            </i>
                        </a>
                        <a class="share_circle" href="#" data-placement="top" data-container="body" data-toggle="tooltip" data-trigger="hover" data-original-title="下载长微博图片">
                            <i class="iconfont icon-zhaopian">
                            </i>
                        </a>
                        <a class="share_circle more_share" href="#">
                            更多分享
                        </a>
                    </div>
                </div>
                @include('v2.parts.author_comment')
            </div>
        </div>
        {{-- 底部小火箭、分享、收藏、投稿 --}}
        @include('v2.parts.side_tool')
    </div>
    <div class="note_bottom">
        <div class="container">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div>
                    <div class="main">
                        <div class="recommend_title">
                            被以下专题收入，发现更多相似内容
                            <a href="#">
                                <i class="iconfont icon-shezhi2"></i>
                                投稿管理
                            </a>
                        </div>
                        <div class="include_collection">
                            <a class="collection" href="#" data-target="#detailModal_user" data-toggle="modal">
                                <div class="name">
                                    ＋ 收入我的主题
                                </div>
                            </a>
                            <a class="collection" href="#">
                                <img src="/images/category_08.jpg"/>
                                <div class="name">
                                    剑侠情缘
                                </div>
                            </a>
                            <a class="collection" href="#">
                                <img src="/images/category_01.jpeg"/>
                                <div class="name">
                                    王者荣耀
                                </div>
                            </a>
                            <a class="collection more_collection" href="#">
                                <div class="name">
                                    展开更多
                                    <i class="iconfont icon-xia">
                                    </i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <detailmodal-user></detailmodal-user>
                </div>
                <div>
                    <div class="recommend_note">
                        <div class="recommend_title">
                            推荐阅读
                            <a href="#">
                                更多精彩内容
                                <i class="iconfont icon-youbian">
                                </i>
                            </a>
                        </div>
                        @include('v2.parts.article.article_list_recommend')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@extends('v2.layouts.app')

@section('title')
    热门专题 - 爱你城
@stop
@section('content')
<div id="categories">
    <div class="container">
        <div class="recommend">
            <div class="recommend_img">
                <img src="/images/recommend_banner.png"/>
                <a class="help" href="/v2/detail" target="_blank">
                    <i class="iconfont icon-send1179291easyiconnet">
                    </i>
                    如何创建并玩转专题
                </a>
            </div>
            <div>
                <!-- Nav tabs -->
                <ul class="trigger_menu" role="tablist">
                    <li class="active" role="presentation">
                        <a aria-controls="tuijian" data-toggle="tab" href="#tuijian" role="tab">
                            <i class="iconfont icon-tuijian1">
                            </i>
                            推荐
                        </a>
                    </li>
                    <li role="presentation">
                        <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                            <i class="iconfont icon-huo">
                            </i>
                            热门
                        </a>
                    </li>
                    <li role="presentation">
                        <a aria-controls="chengshi" data-toggle="tab" href="#chengshi" role="tab">
                            <i class="iconfont icon-icon2">
                            </i>
                            城市
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tuijian" role="tabpanel">
                        <div class="row">
                            <div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="/v2/category/2" target="_blank">
                                        <img class="avatar_lg" src="/images/category_02.jpg"/>
                                        <div class="headline">
                                            谈谈情，说说爱
                                        </div>
                                        <p class="abstract">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度
                                        </p>
                                    </a>
                                    <a class="btn_base btn_follow" href="javascript:;">
                                        <span>
                                            ＋ 关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="/v2/category/2" target="_blank">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="/v2/category/2" target="_blank">
                                        <img class="avatar_lg" src="/images/category_02.jpg"/>
                                        <div class="headline">
                                            谈谈情，说说爱
                                        </div>
                                        <p class="abstract">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度
                                        </p>
                                    </a>
                                    <a class="btn_base btn_follow" href="javascript:;">
                                        <span>
                                            ＋ 关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="/v2/category/2" target="_blank">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div><div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="/v2/category/2" target="_blank">
                                        <img class="avatar_lg" src="/images/category_02.jpg"/>
                                        <div class="headline">
                                            谈谈情，说说爱
                                        </div>
                                        <p class="abstract">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度
                                        </p>
                                    </a>
                                    <a class="btn_base btn_follow" href="javascript:;">
                                        <span>
                                            ＋ 关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="/v2/category/2" target="_blank">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div><div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="/v2/category/2" target="_blank">
                                        <img class="avatar_lg" src="/images/category_02.jpg"/>
                                        <div class="headline">
                                            谈谈情，说说爱
                                        </div>
                                        <p class="abstract">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度
                                        </p>
                                    </a>
                                    <a class="btn_base btn_follow" href="javascript:;">
                                        <span>
                                            ＋ 关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="/v2/category/2" target="_blank">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="load_more" href="javascript:;">
                            加载更多
                        </a>
                    </div>
                    <div class="tab-pane fade" id="huo" role="tabpanel">
                        <div class="row">
                            <div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="/v2/category/2" target="_blank">
                                        <img class="avatar_lg" src="/images/category_08.jpg"/>
                                        <div class="headline">
                                            剑侠情缘
                                        </div>
                                        <p class="abstract">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度
                                        </p>
                                    </a>
                                    <a class="btn_base btn_follow" href="javascript:;">
                                        <span>
                                            ＋ 关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="/v2/category/2" target="_blank">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="load_more" href="javascript:;">
                            加载更多
                        </a>
                    </div>
                    <div class="tab-pane fade" id="chengshi" role="tabpanel">
                        <div class="row">
                            <div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="/v2/category/2" target="_blank">
                                        <img class="avatar_lg" src="/images/category_05.jpg"/>
                                        <div class="headline">
                                            好中文
                                        </div>
                                        <p class="abstract">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度
                                        </p>
                                    </a>
                                    <a class="btn_base btn_follow" href="javascript:;">
                                        <span>
                                            ＋ 关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="/v2/category/2" target="_blank">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="load_more" href="javascript:;">
                            加载更多
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@extends('v1.layouts.app')

@section('title')
    热门专题 - 爱你城
@stop
@section('content')
<div id="categories">
    <div class="container">
        <div class="recommend">
            <img src="//cdn2.jianshu.io/assets/web/recommend-collection-58f8968955ecbeb8f8f3b4cd95ec76be.png"/>
            <a class="help" href="#">
                <i class="iconfont icon-yiwen">
                </i>
                如何创建并玩转专题
            </a>
            <div>
                <!-- Nav tabs -->
                <ul class="trigger_menu" role="tablist">
                    <li class="active" role="presentation">
                        <a aria-controls="tuijian" data-toggle="tab" href="#tuijian" role="tab">
                            <i class="iconfont icon-tuijian">
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
                            <i class="iconfont icon-chengshi">
                            </i>
                            城市
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tuijian" role="tabpanel">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="load_more" href="#">
                            加载更多
                        </a>
                    </div>
                    <div class="tab-pane fade" id="huo" role="tabpanel">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="load_more" href="#">
                            加载更多
                        </a>
                    </div>
                    <div class="tab-pane fade" id="chengshi" role="tabpanel">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                <div class="collection_wrap">
                                    <a href="#">
                                        <img class="avatar_collection" src="//upload.jianshu.io/collections/images/49/66ba9fdegw1e61syw6tk6j20bj0go0wo.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/180/h/180"/>
                                        <h4 class="name">
                                            谈谈情，说说爱
                                        </h4>
                                        <p class="collection_description">
                                            柏拉图说每个恋爱中的人都是诗人，这里并不要求你一定要写得诗情画意，态度...
                                        </p>
                                    </a>
                                    <a class="follow" href="#">
                                        <i class="iconfont icon-jia">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                    </a>
                                    <hr/>
                                    <div class="count">
                                        <a href="#">
                                            70077篇文章
                                        </a>
                                        · 1103.3K人关注
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="load_more" href="#">
                            加载更多
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

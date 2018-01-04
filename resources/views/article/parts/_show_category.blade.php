    <div class="note_bottom">
        <div class="container">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div>
                    <div class="main">
                        <div class="recommend_title">
                            被以下专题收入，发现更多相似内容
                        </div>
                        <div class="include_collection">
                            <a class="collection" href="javascript:;">
                                <div class="name">
                                    ＋ 收入我的主题
                                </div>
                            </a>
                          @foreach($article->categories as $category)
                            <a class="collection" href="/{{ $category->name_en }}">
                                <img src="{{ $category->logo }}">
                                <div class="name">
                                    {{ $category->name }}
                                </div>
                            </a>
                          @endforeach
                        </div> 
                    </div>
                         <detailmodal-user>
                        </detailmodal-user>
                        <detailmodal-home>
                        </detailmodal-home>
                </div>
                <div>
                    <div class="recommend_note">
                        <div class="meta">
                            <div class="recommend_title">
                                推荐阅读
                                <a href="javascript:;">
                                    更多精彩内容
                                    <i class="iconfont icon-youbian">
                                    </i>
                                </a>
                            </div>
                        </div>
                        @include('parts.list.article_list_category',['articles'=>$data['related']])
                    </div>
                </div>
            </div>
        </div>
    </div>
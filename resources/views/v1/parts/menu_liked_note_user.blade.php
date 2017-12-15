{{-- 用户页喜欢文章的标签页 --}}
<div>
    <!-- Nav tabs -->
    <ul class="trigger_menu" role="tablist">
        <li role="presentation">
            <a aria-controls="follow" data-toggle="tab" href="#follow" role="tab">
                关注的专题/文集 3
            </a>
        </li>
        <li class="active" role="presentation">
            <a aria-controls="likes" data-toggle="tab" href="#likes" role="tab">
                喜欢的文章 7
            </a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade" id="follow" role="tabpanel">
            <ul class="user_list">
                <li>
                    <div>
                        <a class="avatar avatar_collection" href="#">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <div class="info">
                            <a class="name" href="#">
                                懂点医
                            </a>
                            <div class="meta">
                                <a href="#">
                                    懂点医
                                </a>
                                收录了28篇文章，6人关注
                            </div>
                        </div>
                        <a class="follow" href="javascript:;">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                    </div>
                </li>
                <li>
                    <div>
                        <a class="avatar avatar_collection" href="#">
                            <img src="/images/category_02.jpg"/>
                        </a>
                        <div class="info">
                            <a class="name" href="#">
                                懂美味
                            </a>
                            <div class="meta">
                                <a href="#">
                                    樂活人生百态
                                </a>
                                收录了31篇文章，6人关注
                            </div>
                        </div>
                        <a class="follow" href="javascript:;">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                    </div>
                </li>
                <li>
                    <div>
                        <a class="avatar avatar_collection" href="#">
                            <img src="/images/category_09.png"/>
                        </a>
                        <div class="info">
                            <a class="name" href="#">
                                王者荣耀
                            </a>
                            <div class="meta">
                                收录了19篇文章，4人关注
                            </div>
                        </div>
                        <a class="follow" href="javascript:;">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="tab-pane fade in active" id="likes" role="tabpanel">
            @include('v1.parts.article_list_nocategory')
        </div>
    </div>
</div>
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
            @include('parts.list.article_list_category',['articles'=>$data['articles']])
        </div>
        <div class="tab-pane fade" id="dongtai" role="tabpanel">
            <ul class="article_list">
                @include('user.parts.user_acive')
            </ul>
        </div>
        <div class="tab-pane fade" id="pinglun" role="tabpanel">
            @include('parts.list.article_list_category',['articles'=>$data['commented']])
        </div>
        <div class="tab-pane fade" id="huo" role="tabpanel">
            @include('parts.list.article_list_category',['articles'=>$data['hot']])
        </div>
    </div>
</div>
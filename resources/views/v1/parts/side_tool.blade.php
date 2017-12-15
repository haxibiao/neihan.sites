{{-- 底部小火箭、分享、收藏、投稿 --}}
<div class="side_tool">
    <ul>
        <li class="toup_rocket" data-placement="right" data-toggle="tooltip" data-container="body" title="回到顶部">
            <a href="javascript:;">
                <i class="iconfont icon-xiangxiajiantou-copy"></i>
            </a>
        </li>
        <li data-placement="left" data-toggle="modal" data-container="body" title="将文章加入专题" data-target="#detailModal_user">
            <a href="javascript:;">
                <i class="iconfont icon-jia1"></i>
            </a>
        </li>
        <li data-placement="left" data-toggle="modal" data-container="body" title="文章投稿" data-target="#detailModal_home">
            <a href="javascript:;">
                <i class="iconfont icon-tougaoguanli"></i>
            </a>
        </li>
        <li data-placement="left" data-toggle="tooltip" data-container="body" title="收藏文章">
            <a href="javascript:;">
                <i class="iconfont icon-shoucang"></i>
            </a>
        </li>
        <li data-placement="left" data-toggle="tooltip" data-container="body" title="取消收藏文章">
            <a href="javascript:;">
                <i class="iconfont icon-shoucang1"></i>
            </a>
        </li>
        <li data-placement="left" data-toggle="tooltip" data-container="body" title="分享文章">
            <a href="javascript:;">
                <i class="iconfont icon-fenxiang1"></i>
            </a>
        </li>
    </ul>
</div>

{{-- modal --}}
<detailmodal-user>
</detailmodal-user>
<detailmodal-home></detailmodal-home>

@push('scripts')
<script>
    $(function() {
        // 小火箭
        $(window).on("scroll",function () {
            if($(window).scrollTop()>1000) {
                $(".toup_rocket").fadeIn(300);
            }else {
                $(".toup_rocket").fadeOut(300);
            }
        });
        $(".toup_rocket").on("click",function () {
            $("body,html").animate({"scrollTop": 0 }, 1000);
        });
    });
</script>
@endpush

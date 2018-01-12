{{-- 底部小火箭、分享、收藏、投稿 --}}
<div class="side_tool">
    <ul>
        <li class="toup_rocket" data-container="body" data-original-title="回到顶部" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a href="javascript:;" class="function_button">
                <i class="iconfont icon-xiangshangjiantou">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="将文章加入专题" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a data-target="#detailModal_user" data-toggle="modal" href="#" class="js_submit_button">
                <i class="iconfont icon-jia1">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="文章投稿" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a data-target="#detailModal_home" data-toggle="modal" href="#" class="js_submit_button">
                <i class="iconfont icon-tougaoguanli">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="收藏文章" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a href="javascript:;" class="function_button">
                <i class="iconfont icon-shoucang">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="取消收藏文章" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a href="javascript:;" class="function_button">
                <i class="iconfont icon-shoucang1">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="文章分享" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <share-modal class="function_button" placement="left"></share-modal>
        </li>
    </ul>
</div>

<detailmodal-home></detailmodal-home>

@push('scripts')
<script>
    $(function() {
        // 小火箭
        $(window).on("scroll",function() {
            if($(window).scrollTop()>1000) {
                $(".toup_rocket").fadeIn(300);
            }else {
                $(".toup_rocket").fadeOut(300);
            }
        });
        $(".toup_rocket").on("click",function() {
            $("body,html").animate({"scrollTop": 0 }, 1000);
        });

        $(".share").click(function() {
          $(".tooltip").hide();
        });
    });
</script>
@endpush

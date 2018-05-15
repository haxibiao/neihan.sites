{{-- 底部小火箭、分享、收藏、投稿 --}}
<div class="side_tool">
    <ul>
        <li class="toup_rocket" data-container="body" data-original-title="回到顶部" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a href="javascript:;">
                <i class="iconfont icon-xiangxiajiantou-copy">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="将文章加入专题" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a data-target="#detailModal_user" data-toggle="modal" href="#">
                <i class="iconfont icon-jia1">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="文章投稿" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a data-target="#detailModal_home" data-toggle="modal" href="#">
                <i class="iconfont icon-tougaoguanli">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="收藏文章" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a href="javascript:;">
                <i class="iconfont icon-shoucang">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="取消收藏文章" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a href="javascript:;">
                <i class="iconfont icon-shoucang1">
                </i>
            </a>
        </li>
        <li class="share" data-container="body" data-original-title="分享文章" data-placement="left" data-toggle="tooltip">
            <a data-toggle="dropdown" href="javascript:;">
                <i class="iconfont icon-fenxiang1">
                </i>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#">
                        <i class="iconfont icon-weixin1">
                        </i>
                        <span>
                            分享到微信
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="iconfont icon-sina">
                        </i>
                        <span>
                            分享到微博
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="iconfont icon-zhaopian">
                        </i>
                        <span>
                            下载长微博图片
                        </span>
                    </a>
                </li>
            </ul>
        </li>
        {{--
        <li data-container="body" data-original-title="分享文章" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a data-content="<ul class='share'>
                <li>
                    <a href='#'>
                        <i class='iconfont icon-weixin1'></i>
                        <span>分享到微信</span>
                    </a>
                </li>
                <li>
                    <a href='#'>
                        <i class='iconfont icon-sina'></i>
                        <span>分享到微博</span>
                    </a>
                </li>
                <li>
                    <a href='#'>
                        <i class='iconfont icon-zhaopian'></i>
                        <span>下载长微博图片</span>
                    </a>
                </li>
            </ul>" data-html="true" data-placement="left" data-toggle="popover" data-trigger="focus" role="button" tabindex="0">
            </a>
        </li>
        --}}
    </ul>
</div>

{{-- modal --}}
<detailmodal-user>
</detailmodal-user>
<detailmodal-home>
</detailmodal-home>

@push('scripts')
<script>
    $(function() {
        // $('[data-toggle="popover"]').popover();

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

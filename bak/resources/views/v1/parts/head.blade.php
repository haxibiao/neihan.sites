{{-- 未登录的头部 --}}
<header>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar_wrp">
                <div class="navbar-header">
                    <button aria-expanded="false" class="navbar-toggle collapsed" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" type="button">
                        <span class="sr-only">
                            Toggle navigation
                        </span>
                        <span class="icon-bar">
                        </span>
                        <span class="icon-bar">
                        </span>
                        <span class="icon-bar">
                        </span>
                    </button>
                    <a class="navbar-brand" href="/v1">
                        <img alt="Logo" src="/logo/ainicheng.com.jpg"/>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="tab v1">
                            <a href="/v1">
                                <i class="iconfont icon-xin">
                                </i>
                                <span class="menu_text">
                                    发现
                                </span>
                            </a>
                        </li>
                        <li class="tab">
                            <a href="#">
                                <i class="iconfont icon-ordinarymobile">
                                </i>
                                <span class="menu_text">
                                    下载App
                                </span>
                            </a>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <div class="search_wrp">
                                <input class="form-control" placeholder="搜索" type="text"/>
                                <i class="iconfont icon-sousuo">
                                </i>
                                <div class="hot_search_wrp hidden-xs">
                                    <div class="hot_search">
                                        <div class="litter_title">
                                            热门搜索
                                            <a class="more" href="javascript:;" target="_blank">
                                                <i class="iconfont icon-shuaxin">
                                                </i>
                                                换一批
                                            </a>
                                        </div>
                                        <ul>
                                            <li>
                                                <a href="#" target="_blank">
                                                    王者荣耀
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank">
                                                    恋爱七招
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank">
                                                    吃鸡
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank">
                                                    故事
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        {{-- <li>
                            <a class="style_mode_btn" href="javascript:;">
                                <span>
                                    Aa
                                </span>
                            </a>
                        </li> --}}
                        <li>
                            <a href="/v1/sign_in">
                                <span>
                                    登录
                                </span>
                            </a>
                        </li>
                        <li class="register">
                            <a href="/v1/sign_up">
                                <span>
                                    注册
                                </span>
                            </a>
                        </li>
                        <li class="creation">
                            <a href="#">
                                <i class="iconfont icon-maobi">
                                </i>
                                <span>
                                    写文章
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
@push('scripts')
<script>
    $(function(){
        var current_path = window.location.pathname.replace('/','');
        current_path = current_path.replace('/','');
        if($('.navbar_wrp').has('.'+current_path)){
            $('.navbar_wrp').find('.'+current_path).addClass('active');
        };

        $('.dropdown-toggle').dropdown();
        $('[data-toggle="tooltip"]').tooltip();

        $('.form-control').focus(function(){
            $(this).siblings('.hot_search_wrp').css({'visibility':'visible','opacity':1});
        });
        $(document).click(function(e){
            if($(e.target).parents('.search_wrp')[0]!=$('.search_wrp')[0]){
                $('.hot_search_wrp').css({'visibility':'hidden','opacity':0});
            }
        });
    })
</script>
@endpush

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
                    <a class="navbar-brand" href="/">
                        <img alt="Logo" src="/logo/ainicheng.com.index.jpg"/>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="tab {{ get_active_css('/') }}">
                            <a href="/" class="identifier">
                                <i class="iconfont icon-faxian">
                                </i>
                                <span class="menu_text">
                                    发现
                                </span>
                            </a>
                        </li>
                        <li class="tab {{ get_active_css('question') }}">
                            <a href="/question" class="identifier">
                                <i class="iconfont icon-help">
                                </i>
                                <span class="menu_text">
                                    问答
                                </span>
                            </a>
                        </li>
                        <li class="tab">
                            <a href="#" class="identifier">
                                <i class="iconfont icon-ordinarymobile">
                                </i>
                                <span class="menu_text">
                                    下载App
                                </span>
                            </a>
                        </li>
                    </ul>
                    <search></search>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/login">
                                <span>
                                    登录
                                </span>
                            </a>
                        </li>
                        <li class="register">
                            <a href="/register">
                                <span class="btn_base btn_register">
                                    注册
                                </span>
                            </a>
                        </li>
                        <li class="creation">
                            <a href="/login">
                                <span class="btn_base btn_creation">
                                    <i class="iconfont icon-maobi">
                                    </i>
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
        // var current_path = window.location.pathname.replace('/','');
        // current_path = current_path.replace('/','');
        // if($('.navbar_wrp').has('.'+current_path)){
        //     $('.navbar_wrp').find('.'+current_path).addClass('active');
        // };

        $('.dropdown-toggle').dropdown();
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();

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

{{-- 登录后的头部 --}}
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
                        <img alt="Logo" src="/logo/ainicheng.com.jpg"/>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="tab v1">
                            <a href="/" class="identifier">
                                <i class="iconfont icon-xin">
                                </i>
                                <span class="menu_text">
                                    发现
                                </span>
                            </a>
                        </li>
                        <li class="tab follow">
                            <a href="/follow" class="identifier">
                                <i class="iconfont icon-huizhang">
                                </i>
                                <span class="menu_text">
                                    关注
                                </span>
                            </a>
                        </li>
                        <li class="tab notification" class="identifier">
                            <a href="/notification" class="identifier">
                                <i class="iconfont icon-zhongyaogaojing">
                                </i>
                                <span class="menu_text">
                                    消息
                                </span>
                                @php
                                   $unreads_all=array_sum(Auth::user()->unreads());   
                                @endphp
                                @if($unreads_all)
                                    <span class="badge">
                                        {{ $unreads_all }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/notification#/comments" >
                                        <i class="iconfont icon-xinxi">
                                        </i>
                                        <span>
                                            评论
                                        </span>
                                        <span class="badge">{{ Auth::user()->unreads('comments') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/notification#/chats">
                                        <i class="iconfont icon-email">
                                        </i>
                                        <span>
                                            私信
                                        </span>
                                        <span class="badge">{{ Auth::user()->unreads('chats') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/notification#/requests">
                                        <i class="iconfont icon-tougaoguanli">
                                        </i>
                                        <span>
                                            投稿请求
                                        </span>
                                        <span class="badge">{{ Auth::user()->unreads('requests') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/notification#/likes">
                                        <i class="iconfont icon-xin">
                                        </i>
                                        <span>
                                            喜欢和赞
                                        </span>
                                        <span class="badge">{{ Auth::user()->unreads('likes') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/notification#/follows">
                                        <i class="iconfont icon-jiaguanzhu">
                                        </i>
                                        <span>
                                            关注
                                        </span>
                                        <span class="badge">{{ Auth::user()->unreads('follows') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/notification#/tip">
                                        <i class="iconfont icon-zanshangicon">
                                        </i>
                                        <span>
                                            赞赏
                                        </span>
                                        <span class="badge">{{ Auth::user()->unreads('tips') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/notification#/others">
                                        <i class="iconfont icon-gengduo">
                                        </i>
                                        <span>
                                            其他消息
                                        </span>
                                        <span class="badge">{{ Auth::user()->unreads('others') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <search></search>

                    <ul class="nav navbar-nav navbar-right">
{{--                         <li>
                            <a class="style_mode_btn" href="javascript:;">
                                Aa
                            </a>
                        </li> --}}
                        <li class="tab own">
                            <a class="avatar" href="/home">
                                <img src="{{ Auth::user()->avatar() }}"/>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/user/{{ Auth::id() }}/likes">
                                        <i class="iconfont icon-yonghu01">
                                        </i>
                                        <span>
                                            我的主页
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/home">
                                        <i class="iconfont icon-unie646">
                                        </i>
                                        <span>
                                            报表主页
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/user/{{ Auth::id() }}/favorites">
                                        <i class="iconfont icon-biaoqian">
                                        </i>
                                        <span>
                                            收藏的文章
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/user/{{ Auth::id() }}/likes">
                                        <i class="iconfont icon-03xihuan">
                                        </i>
                                        <span>
                                            喜欢的文章
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/wallet">
                                        <i class="iconfont icon-qianbao">
                                        </i>
                                        <span>
                                            我的钱包
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/setting">
                                        <i class="iconfont icon-shezhi">
                                        </i>
                                        <span>
                                            设置
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="iconfont icon-svg37">
                                        </i>
                                        <span>
                                            帮助与反馈
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                     <i class="iconfont icon-tuichu1">
                                            </i>
                                        退出登录
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <li class="creation">
                            <a href="/article/create">
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
        // var current_path = window.location.pathname.replace('/v1/','');
        // current_path = current_path.replace('/','');
        // if($('.navbar_wrp').has('.'+current_path)){
        //     $('.navbar_wrp').find('.'+current_path).addClass('active');
        // };

        $('.dropdown-toggle').dropdown();

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

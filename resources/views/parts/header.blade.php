@if(!is_in_app())
<nav class="navbar navbar-default navbar-fixed-top" style="{{ get_top_nav_bg() }}">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <img src="/image/{{ env('APP_DOMAIN') }}.small.jpg" alt="{{ config('app.name', 'Laravel') }}" class="right10" style="max-height: 50px" />
            {{-- <a class="navbar-brand" href="{{ url('/') }}" title="{{ config('app.name', 'Laravel') }}">
                              
            </a> --}}
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
             
             @include('parts.left_navs')

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}"　style="{{ get_top_nav_color() }}">登录</a></li>
                    <li><a href="{{ route('register') }}"　style="{{ get_top_nav_color() }}">注册</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"　style="{{ get_top_nav_color() }}">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="/home">
                                    个人面板
                                </a>
                            </li>
                            <li>
                                <a href="/user/{{ Auth::user()->id }}/favorites">
                                    我的收藏
                                </a>
                            </li>
                            <li>
                                <a href="/profile">
                                    修改资料
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    退出登录
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@endif
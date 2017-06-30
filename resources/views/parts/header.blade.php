<nav class="navbar navbar-default navbar-static-top">
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
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
             <ul class="nav navbar-nav">
              <li class="{{ get_active_css('/') }}"><a href="/">首页 <span class="sr-only">(current)</span></a></li>
              <li class="{{ get_active_css('zhongyi') }}"><a href="/zhongyi">中医</a></li>
              <li class="dropdown {{ get_active_css('xiyi') }}">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">西医 <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="/xiyi">西医</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="/xiyi/neikexue">内科学</a></li>
                  <li><a href="/xiyi/neikexue">外科学</a></li>
                  <li><a href="/xiyi/neikexue">妇产科学</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="/xiyi/neikexue">儿科学</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="/xiyi/neikexue">神经病学</a></li>
                </ul>
              </li>
            </ul>
            <form class="navbar-form navbar-left">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="搜索...">
              </div>
              <button type="submit" class="btn btn-default">搜索</button>
            </form>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">登录</a></li>
                    <li><a href="{{ route('register') }}">注册</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                          <li>
                                <a href="/home">
                                    个人面板
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
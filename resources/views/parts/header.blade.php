@if(!is_in_app())
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
            <img src="/{{ env('APP_DOMAIN') }}.small.jpg" alt="{{ config('app.name', 'Laravel') }}" class="right10" style="max-height: 50px" />
            {{-- <a class="navbar-brand" href="{{ url('/') }}" title="{{ config('app.name', 'Laravel') }}">
                              
            </a> --}}
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
             <ul class="nav navbar-nav">
              
              <li class="{{ get_active_css('/') }}"><a href="/">首页 <span class="sr-only">(current)</span></a></li>
              @foreach($category_items as $item)
                @if($item->level == 0)
                  @if(!$item->has_child)
                    <li class="{{ get_active_css($item->name_en) }}"><a href="/{{ $item->name_en }}">{{ $item->name }}</a></li>
                  @else 
                    <li class="dropdown {{ get_active_css($item->name_en) }}">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $item->name }} <span class="caret"></span></a>
                      <ul class="dropdown-menu menu-top">
                        <li><a href="/{{ $item->name_en }}">{{ $item->name }}</a></li>
                        <li role="separator" class="divider"></li>
                        @foreach($category_items as $item_sub)
                          @if($item_sub->parent_id == $item->id)
                            {{-- <li><a href="/{{ $item_sub->name_en }}">{{ $item_sub->name }}</a></li> --}}
                            @if(!$item_sub->has_child)
                              <li class="{{ get_active_css($item_sub->name_en) }}"><a href="/{{ $item_sub->name_en }}">{{ $item_sub->name }}</a></li>
                            @else 
                              <li class="dropdown-submenu {{ get_active_css($item_sub->name_en) }}">
                                <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $item_sub->name }}</a>
                                <ul class="dropdown-menu">
                                  <li><a href="/{{ $item_sub->name_en }}">{{ $item_sub->name }}</a></li>
                                  <li role="separator" class="divider"></li>
                                  @foreach($category_items as $item_subsub)
                                    @if($item_subsub->parent_id == $item_sub->id)
                                      <li><a href="/{{ $item_subsub->name_en }}">{{ $item_subsub->name }}</a></li>
                                    @endif
                                  @endforeach
                                </ul>
                              </li>
                            @endif
                          @endif
                        @endforeach
                      </ul>
                    </li>
                  @endif
                @endif
              @endforeach
              

            </ul>
            
            {{-- <form class="navbar-form navbar-left">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="搜索...">
              </div>
              <button type="submit" class="btn btn-default">搜索</button>
            </form> --}}

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
@endif
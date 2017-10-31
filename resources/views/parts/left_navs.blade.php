<ul class="nav navbar-nav">

  <li class="{{ get_active_css('/') }}"><a style="{{ get_top_nav_color() }}" href="/">首页</a></li>
  @foreach($category_items as $item)
    @if($item->level == 0)
      @if(!$item->has_child)
        <li class="{{ get_active_css($item->name_en) }}"><a style="{{ get_top_nav_color() }}" href="/{{ $item->name_en }}">{{ $item->name }}</a></li>
      @else
        <li class="dropdown {{ get_active_css($item->name_en) }}">
          <a style="{{ get_top_nav_color() }}" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">{{ $item->name }} <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/{{ $item->name_en }}">{{ $item->name }}</a></li>
            <li role="separator" class="divider"></li>
            @foreach($category_items as $item_sub)
              @if($item_sub->parent_id == $item->id)
                {{-- <li><a href="/{{ $item_sub->name_en }}">{{ $item_sub->name }}</a></li> --}}
                @if(!$item_sub->has_child)
                  <li class="{{ get_active_css($item_sub->name_en) }}"><a href="/{{ $item_sub->name_en }}">{{ $item_sub->name }}</a></li>
                @else
                  <li class="dropdown dropdown-submenu {{ get_active_css($item_sub->name_en) }}">
                    <a tabindex="-1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">{{ $item_sub->name }}</a>
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
  <li class="{{ get_active_css('video') }}"><a style="{{ get_top_nav_color() }}" href="/video">视频</a></li>
  <li class="{{ get_active_css('video') }}"><a style="{{ get_top_nav_color() }}" href="/new">最新内容</a></li>
</ul>
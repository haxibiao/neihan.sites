<div>
    <!-- Nav tabs -->
    <ul class="trigger_menu" role="tablist">
        <li role="presentation">
            <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                <i class="iconfont icon-svg37">
                </i>
                最新评论
            </a>
        </li>
        <li class="active" role="presentation">
            <a aria-controls="shoulu" data-toggle="tab" href="#shoulu" role="tab">
                <i class="iconfont icon-wenji">
                </i>
                最新收录
            </a>
        </li>
        <li role="presentation">
            <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                <i class="iconfont icon-huo">
                </i>
                热门
            </a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active" id="pinglun" role="tabpanel">
            @include('category.parts.category_item',['articles'=>$data['commented']])
            @if(Auth::check())
            <article-list api="/{{ $category->name_en }}?commented=1" start-page="2" show-category="1">
            </article-list>
            @else
                {{ $data['commented']->links() }}
            @endif
        </div>
        <div class="tab-pane fade" id="shoulu" role="tabpanel">
            @include('category.parts.category_item',['articles'=>$data['collected']])
            @if(Auth::check())
            <article-list api="/{{ $category->name_en }}?collected=1" start-page="2" show-category="1">
            </article-list>
            @else
                {{ $data['collected']->links() }}
            @endif
        </div>
        <div class="tab-pane fade" id="huo" role="tabpanel">
            @include('category.parts.category_item',['articles'=>$data['hot']])
            @if(Auth::check())
            <article-list api="/{{ $category->name_en }}?hot=1" start-page="2" show-category="1">
            </article-list>
            @else
                {{ $data['hot']->links() }}
            @endif
        </div>
    </div>
</div>
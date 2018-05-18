{{-- 问答分类 --}}
<div class="classification">
    <a class="collection pay {{ request('cid') == -1 ? 'active' : '' }}" href="/question?cid=-1">
        <img src="/images/money.small.jpg"/>
        <div class="name">
            付费
        </div>
    </a>
    <a class="collection huo {{ request()->path() == 'question' && empty(request('cid')) ? 'active' : '' }}" href="/question">
        <img src="/images/hot.small.jpg"/>
        <div class="name">
            热门
        </div>
    </a>
    @foreach($categories as $category)
    <a class="collection {{ request('cid') == $category->id ? 'active' : '' }}" href="/question/?cid={{ $category->id }}">
        <img src="{{ $category->logo }}"/>
        <div class="name">
            {{ $category->name }}
        </div>
    </a>
    @endforeach
    <a class="collection more_collection" href="/categories">
        <div class="name">
            更多
            <i class="iconfont icon-youbian">
            </i>
        </div>
    </a>
{{--       <a class="collection" href="/category/2">
        <img src="/images/details_04.jpeg"/>
        <div class="name">
            娱乐
        </div>
    </a>
    <a class="collection" href="/category/2">
        <img src="/images/category_08.jpg"/>
        <div class="name">
            恋爱
        </div>
    </a>
    <a class="collection" href="/category/2">
        <img src="/images/category_03.jpg"/>
        <div class="name">
            游戏
        </div>
    </a>
    <a class="collection" href="/category/2">
        <img src="/images/category_04.jpeg"/>
        <div class="name">
            昵称
        </div>
    </a> --}}
{{--         <a class="collection more_collection" href="/interlocution/more">
        <div class="name">
            更多
            <i class="iconfont icon-youbian">
            </i>
        </div>
    </a> --}}
</div>

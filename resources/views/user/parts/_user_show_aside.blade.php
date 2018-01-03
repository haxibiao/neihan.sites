<div class="aside_list">
    个人介绍
</div>

<div class="description">
    <div class="intro">
        {{ $user->introduction }}
    </div>
</div>

<ul class="aside_list user_dynamic">
    <li>
        <a href="#">
            <i class="iconfont icon-duoxuan">
            </i>
            <span>
                关注的专题/文集
            </span>
        </a>
    </li>
    <li>
        <a href="#">
            <i class="iconfont icon-xin">
            </i>
            <span>
                喜欢的文章
            </span>
        </a>
    </li>
</ul>
<div>
    <p class="litter_title">
        创建的专题
    </p>
    <ul class="aside_list">
        @each('user.parts.category_item', $user->categories()->orderBy('id','desc')->take(5)->get(), 'category')
    </ul>
    <p class="litter_title">
        我的文集
    </p>
    <ul class="aside_list">
        @each('user.parts.collection_item', $user->collections()->orderBy('id','desc')->take(5)->get(), 'collection')
    </ul>
</div>

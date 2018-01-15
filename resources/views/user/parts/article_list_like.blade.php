{{-- 有取消喜欢的文章摘要 --}}
<ul class="article_list">
    <li class="article_item {{ $article->primaryImage()?'have_img':'' }}">
        <a class="wrap_img" href="/article/{{ $article->id }}" target="_blank">
            <img src="{{ $article->primaryImage() }}"/>
        </a>
        <div class="content">
            <div class="author">
                <a class="avatar" href="/v1/user" target="_blank">
                    <img src="{{ $article->user->avatar }}"/>
                </a>
                <div class="info_meta">
                    <a href="/user/{{ $article->user->id }}" target="_blank">
                        {{ $article->user->name }}
                    </a>
                    <a href="/v1/detail" target="_blank">
                       <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者"  class="badge_icon_xs" />
                    </a>
                    <span class="time">
                        {{ $article->timeAgo() }}
                    </span>
                </div>
            </div>
            <a class="headline paper_title" href="/article/{{ $article->id }}" target="_blank">
                {{ $article->title }}
            </a>
            <p class="abstract">
                {{ $article->description() }}
            </p>
            <div class="meta">
                <a href="/article/{{ $article->id }}" target="_blank" class="count count_link">
                    <i class="iconfont icon-liulan">
                    </i>
                    {{ $article->hits }}
                </a>
                <a href="/article/{{ $article->id }}" target="_blank" class="count count_link">
                    <i class="iconfont icon-svg37">
                    </i>
                    {{ $article->count_replies }}
                </a>
                <span class="count">
                    <i class="iconfont icon-03xihuan">
                    </i>
                    {{ $article->count_likes }}
                </span>
                <a class="count count_link cancal" href="javascript:;">
                    取消喜欢
                </a>
            </div>
        </div>
    </li>
</ul>
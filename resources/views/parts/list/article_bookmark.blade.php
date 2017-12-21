{{-- 有取消收藏的文章摘要 --}}
@if(!empty($article))
<ul class="article_list">
    <li class="article_item {{ $article->hasImage()?'have_img':'' }}">
        <a class="wrap_img" href="/article/{{ $article->id }}" target="_blank">
            <img src="{{ $article->primaryImage() }}"/>
        </a>
        <div class="content">
            <div class="author">
                <a class="avatar" href="/user/{{ $article->user->id }}" target="_blank">
                    <img src="{{ $article->user->avatar }}"/>
                </a>
                <div class="info">
                    <a href="/v1/user" target="_blank">
                        {{ $article->user->name }}
                    </a>
                    <a href="/v1/detail" target="_blank">
                        <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者"/>
                    </a>
                    <span class="time">
                        {{ $article->timeAgo() }}
                    </span>
                </div>
            </div>
            <a class="title" href="/v1/detail" target="_blank">
                {{ $article->title }}
            </a>
            <p class="abstract">
                {{ $article->description() }}
            </p>
            <div class="meta">
                <a href="javascript:;" target="_blank">
                    <i class="iconfont icon-liulan">
                    </i>
                    {{ $article->hits }}
                </a>
                <a href="javascript:;" target="_blank">
                    <i class="iconfont icon-svg37">
                    </i>
                    {{ $article-> }}
                </a>
                <span>
                    <i class="iconfont icon-03xihuan">
                    </i>
                    {{ $article->likes }}
                </span>
                <a class="cancal" href="javascript:;">
                    取消收藏
                </a>
            </div>
        </div>
    </li>
</ul>
@endif
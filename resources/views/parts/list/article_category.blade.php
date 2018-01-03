{{-- 专为搜索页面准备的文章列表页 --}}
@if(!empty($articles))
   @foreach($articles as $article)
    <li class="article_item {{ $article->hasImage()?'have_img':'' }}">
        <a class="wrap_img" href="/article/{{ $article->id }}" target="_blank">
            <img src="{{ get_small_image($article->image_url) }}"/>
        </a>
        <div class="content">
            <div class="author">
                <a class="avatar" href="/user/{{ $article->user->id }}" target="_blank">
                    <img src="{{ $article->user->avatar() }}"/>
                </a>
                <div class="info">
                    <a href="/user/{{ $article->user->id }}" target="_blank">
                        {{ $article->user->name }}
                    </a>
                    <a href="/user/{{ $article->user->id }}" target="_blank">
                        <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_xs" />
                    </a>
                    <span class="time">
                        {{ diffForHumansCN($article->created_at) }}
                    </span>
                </div>
            </div>
            <a class="headline paper_title" href="/article/{{ $article->id }}" target="_blank">
                {{ $article->title }}
            </a>
            <p class="abstract">
                {{ str_limit(strip_tags($article->body),200) }}
            </p>
            <div class="meta">
                <a class="category_tag" href="/{{ $article->category->name_en }}" target="_blank">
                    {{ $article->category->name }}
                </a>
                <a href="/v1/detail" target="_blank">
                    <i class="iconfont icon-liulan">
                    </i>
                    {{ $article->hits }}
                </a>
                <a href="/v1/detail" target="_blank">
                    <i class="iconfont icon-svg37">
                    </i>
                    {{ $article->count_replies }}
                </a>
                <span>
                    <i class="iconfont icon-03xihuan">
                    </i>
                    {{ $article->count_favorites }}
                </span>
            </div>
        </div>
    </li>
    @endforeach

    {{-- <article-list api="/" start-page="1" /> --}}

@endif
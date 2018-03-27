{{-- 有专题标签的文章摘要 --}}
@if(!empty($articles))
<ul class="article_list">
   @foreach($articles as $article)
    <li class="article_item {{ $article->primaryImage()?'have_img':'' }}">
        @if($article->primaryImage())
        <a class="wrap_img" href="/article/{{ $article->id }}" target="_blank">
            <img src="{{ get_small_image($article->image_url) }}"/>
        </a>
        @endif
        <div class="content">
            <div class="author">
                <a class="avatar" href="/user/{{ $article->user->id }}" target="_blank">
                    <img src="{{ $article->user->avatar() }}"/>
                </a>
                <div class="info_meta">
                    <a href="/user/{{ $article->user->id }}" target="_blank" class="nickname">
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
                <span>{{ $article->title }}</span>
            </a>
            <p class="abstract">
                {{ str_limit(strip_tags($article->body),200) }}
            </p>
            <div class="meta">
                @if(!empty($article->category))
                <a class="category_tag" href="/{{ $article->category->name_en }}" target="_blank">
                    {{ $article->category->name }}
                </a>
                @endif
                <a href="/article/{{ $article->id }}" target="_blank" class="count count_link">
                    <i class="iconfont icon-liulan" >
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
                    {{ $article->count_favorites }}
                </span>
            </div>
        </div>
    </li>
    @endforeach

    @if(Auth::check())
    <article-list api="/" start-page="2" />
    @else 
        {!! $articles->links() !!}
    @endif
</ul>
@endif
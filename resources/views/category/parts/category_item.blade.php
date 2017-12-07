{{-- 没有专题标签的文章摘要 --}}
<ul class="article_list">
    @foreach($articles as $article)
    <li class="article_item have_img">
        <a class="wrap_img" href="/article/{{ $article->id }}" target="_blank">
            <img src="{{ get_small_image($article->image_url) }}"/>
        </a>
        <div class="content">
            <div class="author">
                <a class="avatar" href="/user{{ $article->user->id  }}" target="_blank">
                    <img src="{{ $article->user->avatar() }}"/>
                </a>
                <div class="info">
                    <a href="/user{{ $article->user->id  }}" target="_blank">
                        {{ $article->user->name }}
                    </a>
                    <a href="#" target="_blank">
                        <img src="/images/vip1.png"/>
                    </a>
                    <span class="time">
                        {{ diffForHumansCN($article->created_at) }}
                    </span>
                </div>
            </div>
            <a class="title" href="/article/{{ $article->id }}" target="_blank">
                {{ $article->title }}
            </a>
            <p class="abstract">
                {{ str_limit(strip_tags($article->body),200) }}
            </p>
            <div class="meta">
                <a href="#" target="_blank">
                    <i class="iconfont icon-liulan">
                    </i>
                    {{ $article->hits }}
                </a>
                <a href="#" target="_blank">
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
</ul>
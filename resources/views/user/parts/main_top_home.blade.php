{{-- 个人页左侧头部 --}}
<div class="main_top">
    <a class="avatar avatar_lg" href="/user/{{ $user->id }}">
        <img src="{{ $user->avatar }}"/>
    </a>
    <div class="info_meta">
        <a class="headline nickname" href="/user/{{ $user->id }}">
            <span>
                {{ $user->name }}
            </span>
            @if($user->gender=="男")
            <i class="iconfont icon-nansheng1">
            </i>
            @else
            <i class="iconfont icon-nvsheng1">
            </i>
            @endif
        </a>
        <ul>
            <li>
                <div class="meta_block">
                    <a href="#follow">
                        <p>
                            {{ $user->count_follows }}
                        </p>
                        关注
                        <i class="iconfont icon-youbian">
                        </i>
                    </a>
                </div>
            </li>
            <li>
                <div class="meta_block">
                    <a href="#fans">
                        <p>
                            5
                        </p>
                        粉丝
                        <i class="iconfont icon-youbian">
                        </i>
                    </a>
                </div>
            </li>
            <li>
                <div class="meta_block">
                    <a href="/user/{{ $user->id }}">
                        <p>
                            {{ $user->count_articles }}
                        </p>
                        文章
                        <i class="iconfont icon-youbian">
                        </i>
                    </a>
                </div>
            </li>
            <li>
                <div class="meta_block">
                    <p>
                        {{ $user->count_words }}
                    </p>
                    <div>
                        字数
                    </div>
                </div>
            </li>
            <li>
                <div class="meta_block">
                    <p>
                        {{ $user->count_likes }}
                    </p>
                    <div>
                        收获喜欢
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
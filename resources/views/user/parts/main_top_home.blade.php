{{-- 个人页左侧头部 --}}
<div class="main_top clearfix">
    <a class="avatar" href="/user/{{ $user->id }}">
        <img src="{{ $user->avatar }}"/>
    </a>
    <div class="title">
        <a class="name" href="/user/{{ $user->id }}">
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
    </div>
    <div class="info">
        <ul>
            <li>
                <div class="meta_block">
                    <a href="/v1/home/following">
                        <p>
                            {{ $user->count_follows }}
                        </p>
                        关注
                        <i class="iconfont icon-youbian">
                        </i>
                    </a>
                </div>
            </li>
{{--             <li>
                <div class="meta_block">
                    <a href="/v1/home/followers">
                        <p>
                          {{ $user->count_likes }}
                        </p>
                        粉丝
                        <i class="iconfont icon-youbian">
                        </i>
                    </a>
                </div>
            </li> --}}
            <li>
                <div class="meta_block">
                    <a href="/v1/home">
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
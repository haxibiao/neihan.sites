<div class="main_top">
    <a class="avatar avatar_lg" href="/user/{{ $user->id }}">
        <img src="{{ $user->avatar }}"/>
    </a>
    @if(!$user->isSelf())
      @if(Auth::check())
    <follow followed="{{ Auth::user()->isFollow('users', $user->id)}}" id="{{ $user->id }}" type="users" user-id="{{ Auth::user()->id }}">
    </follow>
    @endif
    <a class="btn_base btn_hollow btn_hollow_sm" href="/chat/with/{{ $user->id }}">
        <span>
            发消息
        </span>
    </a>
    @endif
    <div class="info_meta">
        <a class="headline nickname" href="/user/{{ $user->id }}">
            <span>
                {{ $user->name }}
            </span>
            <i class="iconfont icon-nvsheng1">
            </i>
        </a>
        <ul>
            <li>
                <div class="meta_block">
                    <a href="/user/{{ $user->id }}/followings">
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
                    <a aria-controls="fans" data-toggle="tab" href="#fans" role="tab">
                        <p>
                            {{ $user->count_actions }}
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
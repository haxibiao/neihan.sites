<div>
    <!-- Nav tabs -->
    <ul class="trigger_menu" role="tablist">
        <li class="active" role="presentation">
            <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                <i class="iconfont icon-wenji">
                </i>
                文章
            </a>
        </li>
        <li role="presentation">
            <a aria-controls="dongtai" data-toggle="tab" href="#dongtai" role="tab">
                <i class="iconfont icon-zhongyaogaojing">
                </i>
                动态
            </a>
        </li>
        <li role="presentation">
            <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                <i class="iconfont icon-svg37">
                </i>
                最新评论
            </a>
        </li>
        <li role="presentation">
            <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                <i class="iconfont icon-huo">
                </i>
                热门
            </a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active" id="wenzhang" role="tabpanel">
            @include('parts.list._article_list_category_user',['articles'=>$data['articles']])
            <article-list api="/user/{{ $user->id }}?articles=1" start-page="2" />
        </div>
        <div class="tab-pane fade" id="dongtai" role="tabpanel">
            <ul class="article_list">
                @include('user.parts.user_acive_article',['articles'=>$data['actions_article']])
               @foreach($data['actions'] as $action)
                @include('user.parts.user_acive',['action'=>$action])
               @endforeach
                   <li class="article_item">
                        <div class="author">
                            <a class="avatar" href="/user/{{ $user->id }}" target="_blank">
                                <img src="{{ $user->avatar }}"/>
                            </a>
                            <div class="info_meta">
                                <a class="nickname" href="/user/{{ $user->id }}" target="_blank">
                                    {{ $user->name }}
                                </a>
                                <span class="time">
                                    加入了爱你城 · {{ $user->created_at }}
                                </span>
                            </div>
                        </div>
                    </li>
            </ul>
        </div>
        <div class="tab-pane fade" id="pinglun" role="tabpanel">
            @include('parts.list._article_list_category_user',['articles'=>$data['commented']])
            <article-list api="/user/{{ $user->id }}?commented=1" start-page="2" />
        </div>
        <div class="tab-pane fade" id="huo" role="tabpanel">
            @include('parts.list._article_list_category_user',['articles'=>$data['hot']])
            <article-list api="/user/{{ $user->id }}?hot=1" start-page="2" />
        </div>
    </div>
</div>
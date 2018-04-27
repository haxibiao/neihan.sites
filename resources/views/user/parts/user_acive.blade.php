
@if($action->actionable && $action->actionable->followed_type=='categories')
@php
     $follow = $action->actionable;
     $item = $follow->followed;
@endphp
<li class="article_item">
    <div class="author">
        <a class="avatar" href="/user/{{ $action->user->id }}" target="_blank">
            <img src="{{ $action->user->avatar }}"/>
        </a>
        <div class="info_meta">
            <a class="nickname" href="/v2/user" target="_blank">
                {{ $action->user->name }}
            </a>
            <span class="time">
                关注了专题 ·{{ $item->name }}  ·{{ $action->created_at }}
            </span>
        </div>
    </div>
    <div class="follow_detail">
        <div class="author">
            <a class="avatar avatar_sm avatar_collection" href="/category/{{ $item->name_en }}" target="_blank">
                <img src="{{ $item->logo }}"/>
            </a>
            <a class="btn_base btn_followed" href="javascript:;">
                <span>
                    <i class="iconfont icon-weibiaoti12">
                    </i>
                    <i class="iconfont icon-cha">
                    </i>
                </span>
            </a>
            <div class="info_meta">
                <a class="nickname" href="/category/{{ $item->name_en }}" target="_blank">
                    {{ $item->name }}
                </a>
                <p class="meta">
                    <a href="/user/{{ $item->id }}" target="_blank">
                        {{ $item->user->name }}
                    </a>
                    编，{{ $item->count }} 篇文章，{{ $item->count_follows }} 人关注
                </p>
            </div>
        </div>
        <div class="signature">
            <span>
                   {{ $item->description }}
            </span>
        </div>
    </div>
</li>

@elseif(get_class($action->actionable)=='App\Like')
@php
     $like = $action->actionable;
    $item = $like->liked;
@endphp
<li class="article_item have_img">
    <a class="wrap_img" href="/article/{{ $item->id }}" target="_blank">
        <img src="{{  $item->primaryImage() }}"/>
    </a>
    <div class="content">
        <div class="author">
            <a class="avatar" href="/user/{{ $action->user->id }}" target="_blank">
                <img src="{{ $action->user->avatar }}"/>
            </a>
            <div class="info_meta">
                <a class="nickname" href="/user/{{ $action->user->id }}" target="_blank">
                    {{ $action->user->name }}
                </a>
                <a href="/v2/detail" target="_blank">
                    <img class="badge_icon_xs" data-placement="top" data-toggle="tooltip" src="/images/vip1.png" title="爱你城签约作者"/>
                </a>
                <span class="time">
                    喜欢了文章 · {{ $action->created_at }}
                </span>
            </div>
        </div>
        <a class="headline paper_title" href="/article/{{ $item->id }}" target="_blank">
            <span>
                {{ $item->title }}
            </span>
        </a>
        <p class="abstract">
           {{ $item->description() }}
        </p>
        <div class="meta">
            <div class="origin_author">
                <a href="/user/{{ $item->user->id }}" target="_blank">
                    {{ $item->user->name }}
                </a>
            </div>
            <a class="count count_link" href="/article/{{ $item->id }}" target="_blank">
                <i class="iconfont icon-liulan">
                </i>
                {{ $item->hits }}
            </a>
            <a class="count count_link" href="/article/{{ $item->id }}" target="_blank">
                <i class="iconfont icon-svg37">
                </i>
                {{ $item->count_replies }}
            </a>
            <span class="count">
                <i class="iconfont icon-03xihuan">
                </i>
                {{ $item->count_likes }}
            </span>
        </div>
    </div>
</li>
@elseif(get_class($action->actionable)=='App\Follow')
  @php
       $follow=$action->actionable;
       $item=$follow->followed;
  @endphp
<li class="article_item">
    <div class="author">
        <a class="avatar" href="/user/{{ $action->user->id }}" target="_blank">
            <img src="{{ $action->user->avatar() }}"/>
        </a>
        <div class="info_meta">
            <a class="nickname" href="/user/{{ $action->user->id }}" target="_blank">
                  {{ $action->user->name }}
            </a>
            <span class="time">
                关注了作者 · {{ $action->created_at }}
            </span>
        </div>
    </div>
    <div class="follow_detail">
        <div class="author">
            <a class="avatar avatar_sm" href="/user/{{ $item->id }}" target="_blank">
                <img src="{{ $item->avatar }}"/>
            </a>
            <a class="btn_base btn_followed" href="javascript:;">
                <span>
                    <i class="iconfont icon-weibiaoti12">
                    </i>
                    <i class="iconfont icon-cha">
                    </i>
                </span>
            </a>
            <div class="info_meta">
                <a class="nickname" href="/user/{{ $item->id }}" target="_blank">
                    {{ $item->name }}
                </a>
                <p class="meta">
                    写了 {{ $item->count_words }} 字，被 {{ $item->count_follows }} 人关注，获得了 {{ $item->count_likes }} 个喜欢
                </p>
            </div>
        </div>
        <div class="signature">
            <span>
                {{ $item->introduction   }}
            </span>
        </div>
    </div>
</li>
@elseif(get_class($action->actionable)=='App\Comment')
  @php
      $comment=$action->actionable;
      $item =$comment->commentable;
  @endphp
<li class="article_item">
    <div class="author">
        <a class="avatar" href="/user/{{ $action->user->id }}" target="_blank">
            <img src="{{ $action->user->avatar() }}"/>
        </a>
        <div class="info_meta">
            <a class="nickname" href="/user/{{ $action->user->id }}" target="_blank">
                {{ $action->user->name }}
            </a>
            <span class="time">
                发表了评论 · {{ $action->user->created_at }}
            </span>
        </div>
    </div>
    <div class="comment_dynamic">
        <p class="discuss">
             {{ $comment->body }}
        </p>
        <blockquote>
            <a class="headline paper_title" href="/article/{{ $item->id }}" target="_blank">
                <span>
                    {{ $item->title }}
                </span>
            </a>
            <p class="abstract">
                {{ $item->description() }}
            </p>
            <div class="meta">
                <div class="origin_author">
                    <a href="/user/{{ $item->user->id }}" target="_blank">
                        {{ $item->user->name }}
                    </a>
                </div>
                <a class="count count_link" href="/article/{{ $item->id }}" target="_blank">
                    <i class="iconfont icon-liulan">
                    </i>
                   {{ $item->hits }}
                </a>
                <a class="count count_link" href="/article/{{ $item->id }}" target="_blank">
                    <i class="iconfont icon-svg37">
                    </i>
                    {{ $item->count_replies }}
                </a>
                <span class="count">
                    <i class="iconfont icon-03xihuan">
                    </i>
                    {{ $item->count_likes }}
                </span>
            </div>
        </blockquote>
    </div>
</li>
@endif
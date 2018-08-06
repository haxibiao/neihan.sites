@if($action->actionable && get_class($action->actionable) == 'App\Article')
@php
    $item = $action->actionable;
@endphp
{{-- 发布 --}}
<li class="article-item have-img">
  <a class="wrap-img" href="/article/{{ $item->id }}" target="_blank">
      <img src="{{ $item->primaryImage() }}" alt="">
  </a>
  <div class="content">
    <div class="author">
      <a class="avatar" target="_blank" href="/user/{{ $action->user->id }}">
        <img src="{{ $action->user->avatar() }}" alt="">
      </a>
      <div class="info">
        <a class="nickname" target="_blank" href="/user/{{ $action->user->id }}">{{ $action->user->name }}</a>
        <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
        <span class="time" data-shared-at="2017-11-06T09:20:28+08:00">发表了作品 @timeago($action->created_at)</span>
      </div>
    </div>
    <a class="title" target="_blank" href="/article/{{ $item->id }}"><span>{{ $item->title }}</span></a>
    <p class="abstract">
      {{ $item->description }}
    </p>
    <div class="meta">
      <a target="_blank" href="/article/{{ $item->id }}">
        <i class="iconfont icon-liulan"></i> {{ $item->hits }}
      </a>
      <a target="_blank" href="/article/{{ $item->id }}/#comments">
        <i class="iconfont icon-svg37"></i> {{ $item->count_replies }}
      </a>
      <span><i class="iconfont icon-03xihuan"></i> {{ $item->count_likes }}</span>
    </div>
  </div>
</li>
{{-- 评论 --}}
@elseif($action->actionable && get_class($action->actionable) == 'App\Comment')
@php
    $comment = $action->actionable;
    $item = optional($comment)->commentable;
@endphp
@if($item)

<li class="article-item have-img">
    <a class="wrap-img" href="{{ $item->content_url() }}" target="_blank">
      <img src="{{ $item->primaryImage() }}" alt="">
    </a>
    <div class="content">
        <div class="author">
            <a class="avatar" target="_blank" href="/user/{{ $action->user->id }}">
        <img src="{{ $action->user->avatar() }}" alt="">
      </a>
            <div class="info">
                <a class="nickname" target="_blank" href="/user/{{ $action->user->id }}">{{ $action->user->name }}</a>
                <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
                <span class="time"> 发表了评论 · @timeago($action->created_at)</span>
            </div>
        </div>
        <div class="comment"><p>{!! $comment->body !!}</p></div>
        <blockquote>
            <a class="title" target="_blank" href="{{ $item->content_url() }}"><span>{{ $item->title }}</span></a>
            <p class="abstract">
                {{ $item->description }}
            </p>
            <div class="meta">
                <div class="origin-author">
                    <a target="_blank" href="/user/{{ $action->user->id }}">{{ $item->user->name }}</a>
                </div>
                <a target="_blank" href="/article/{{ $item->id }}">
            <i class="iconfont icon-liulan"></i> {{ $item->hits }}
          </a>
                <a target="_blank" href="/article/{{ $item->id }}/#comments">
            <i class="iconfont icon-svg37"></i> {{ $item->count_replies }}
          </a>
                <span><i class="iconfont icon-03xihuan"></i> {{ $item->count_likes }}</span>
            </div>
        </blockquote>
    </div>
</li>
@endif
{{-- 点赞article --}}
@elseif($action->actionable && get_class($action->actionable) == 'App\Like')
@php
    $like = $action->actionable;
    $item = $like->liked;
@endphp
    @if($item && $like->liked_type == "articles")
        <li class="article-item have-img">
            <a class="wrap-img" href="/article/{{ $item->id }}" target="_blank"><img src="{{ $item->primaryImage() }}" alt=""></a>
            <div class="content">
                <div class="author">
                    <a class="avatar" target="_blank" href="/user/{{ $action->user->id }}"><img src="{{ $action->user->avatar() }}" alt=""></a>
                    <div class="info">
                        <a class="nickname" target="_blank" href="/user/{{ $action->user->id }}">{{ $action->user->name }}</a>
                        <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
                        <span class="time"> 喜欢了作品 · @timeago($like->created_at)</span>
                    </div>
                </div>
                <a class="title" target="_blank" href="/article/{{ $item->id }}"><span>{{ $item->title }}</span></a>
                <p class="abstract">
                    {{ $item->description }}
                </p>
                <div class="meta">
                    <div class="origin-author">
                        <a target="_blank" href="/user/{{ $action->user->id }}">{{ $item->user->name }}</a>
                    </div>
                    <a target="_blank" href="/article/{{ $item->id }}">
                <i class="iconfont icon-liulan"></i> {{ $item->hits }}
              </a>
                    <a target="_blank" href="/article/{{ $item->id }}/#comments">
                <i class="iconfont icon-svg37"></i> {{ $item->count_replies }}
              </a>
                    <span><i class="iconfont icon-03xihuan"></i> {{ $item->count_likes }}</span>
                </div>
            </div>
        </li>
    @elseif($item && $like->liked_type == "comments")
        @php
        $article = $item->commentable;
        @endphp
        @if($article)
        {{-- 点赞comment --}}
        <li class="article-item have-img">
            <a class="wrap-img" href="/article/{{ $article->id }}" target="_blank"><img src="{{ $article->primaryImage() }}" alt=""></a>
            <div class="content">
                <div class="author">
                    <a class="avatar" target="_blank" href="/user/{{ $action->user->id }}"><img src="{{ $action->user->avatar() }}" alt=""></a>
                    <div class="info">
                        <a class="nickname" target="_blank" href="/user/{{ $action->user->id }}">{{ $action->user->name }}</a>
                        <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
                        <span class="time"> 喜欢了作品的评论 · @timeago($like->created_at)</span>
                    </div>
                </div>
                <a class="title" target="_blank" href="/article/{{ $article->id }}"><span>{{ $article->title }}</span></a>
                <p class="abstract">
                    {{ $item->body }}
                </p>
                <div class="meta">
                    <div class="origin-author">
                        <a target="_blank" href="/user/{{ $article->user->id }}">{{ $article->user->name }}</a>
                    </div>
                    <a target="_blank" href="/article/{{ $article->id }}">
                <i class="iconfont icon-liulan"></i> {{ $article->hits }}
              </a>
                    <a target="_blank" href="/article/{{ $item->id }}/#comments">
                <i class="iconfont icon-svg37"></i> {{ $article->count_replies }}
              </a>
                    <span><i class="iconfont icon-03xihuan"></i> {{ $article->count_likes }}</span>
                </div>
            </div>
        </li>
        @endif
    @endif
{{-- 关注 --}}
@elseif($action->actionable && get_class($action->actionable) == 'App\Follow')
    @php
        $follow = $action->actionable;
        $item = $follow->followed;
    @endphp
    @if(get_class($item) == 'App\User')
    <li class="feed-info">
      <div class="content">
            <div class="author">
                <a class="avatar" target="_blank" href="javascript:;"><img src="{{ $action->user->avatar() }}" alt=""></a>
                <div class="info">
                    <a class="nickname" target="_blank" href="/user/{{ $action->user->id }}">{{ $action->user->name }}</a>
                    <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
                    <span class="time"> 关注了作者 · @timeago($action->created_at)</span>
                </div>
            </div>
            <div class="follow-card">
                <div class="note-info">
                    <a class="avatar" href="/user/{{ $item->id }}"><img src="{{ $item->avatar() }}" alt=""></a>
                    {{-- <a class="btn-base btn-follow"><span>＋ 关注</span></a> --}}
                    <follow
                        type="users"
                        id="{{ $item->id }}"
                        user-id="{{ user_id() }}"
                        followed="{{ is_follow('users',$item->id) }}">
                    </follow>
                    <div class="title">
                        <a class="name" href="/user/{{ $item->id }}">{{ $item->name }}</a>
                    </div>
                    <div class="info">
                        <p>写了 {{ $item->count_words }} 字，被 {{ $item->count_follows }} 人关注，获得了 {{ $item->count_likes }} 个喜欢</p>
                    </div>
                </div>
                <p class="signature">
                    {{ $item->introduction }}
                </p>
            </div>
        </div>
    </li>
    @elseif(get_class($item) == 'App\Category')
    <li class="feed-info">
      <div class="content">
        <div class="author">
          <a class="avatar" target="_blank" href="/user/{{ $action->user->id }}">
            <img src="{{ $action->user->avatar() }}" alt="">
          </a>
          <div class="info">
            <a class="nickname" target="_blank" href="/user/{{ $action->user->id }}">{{ $action->user->name }}</a>
            <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
            <span class="time"> 关注了专题 · @timeago($action->created_at)</span>
          </div>
        </div>
        <div class="follow-card">
            <div class="note-info">
                <a class="avatar" href="/{{ $item->name_en }}"><img src="{{ $item->logo() }}" alt=""></a>
                <follow
                    type="categories"
                    id="{{ $item->id }}"
                    user-id="{{ user_id() }}"
                    followed="{{ is_follow('categories', $item->id) }}">
                </follow>
                <div class="title">
                  <a class="name" href="/{{ $item->name_en }}">{{ $item->name }}</a>
                </div>
                <div class="info">
                  <p><a href="/user/{{ $item->user->id }}">{{ $item->user->name }}</a> 创建，{{ $item->count }} 篇作品，{{ $item->count_follows }} 人关注</p>
                </div>
            </div>
            <p class="signature">
                {{ $item->description }}
            </p>
        </div>
      </div>
    </li>
    @endif
@endif
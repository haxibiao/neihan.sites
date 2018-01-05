@if(get_class($action->actionable)=='App\Category')
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
                关注了专题 · {{ $action->created_at }}
            </span>
        </div>
    </div>
    <div class="follow_detail">
        <div class="author">
            <a class="avatar avatar_sm avatar_collection" href="/category/{{ $action->actionable->name_en }}" target="_blank">
                <img src="{{ $action->actionable->logo }}"/>
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
                <a class="nickname" href="/category/{{ $action->actionable->name_en }}" target="_blank">
                    {{ $action->actionable->name }}
                </a>
                <p class="meta">
                    <a href="/user/{{ $action->actionable->id }}" target="_blank">
                        {{ $action->actionable->user->name }}
                    </a>
                    编，{{ $action->actionable->count }} 篇文章，{{ $action->actionable->count_follow }} 人关注
                </p>
            </div>
        </div>
        <div class="signature">
            <span>
                   {{ $action->actionable->description }}
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
                    写了 {{ $item->word }} 字，被 {{ $item->count_follows }} 人关注，获得了 {{ $item->count_likes }} 个喜欢
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
      $item =$comment->commented;
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
{{-- <li class="article_item">
    <div class="author">
        <a class="avatar" href="/v2/user" target="_blank">
            <img src="/images/photo_01.jpg"/>
        </a>
        <div class="info_meta">
            <a class="nickname" href="/v2/user" target="_blank">
                喵星菇凉
            </a>
            <span class="time">
                赞了评论 · 10.30.13:26
            </span>
        </div>
    </div>
    <div class="comment_dynamic">
        <p class="discuss">
            为这篇文章注册简书，我也挺难受大学寝室的环境的，本身自己挺孤僻，习惯一个人待着，最不能忍的是大二的洗澡是十几个开放式没有门的洗澡间···庆幸室友的性格都挺好，现在毕业两年还在联系，今天还和其中一个说想给她设计婚礼请帖的图案的，我玩的很好的高中同学大学5年的室友则是比较针对她，好像是因为拿奖学金之类的事吧，然后处处挤兑，她研究生的室友则是家教啥的都很好，她现在可开心了 我也很替她开心，她读研和我工作在一个城市
        </p>
        <blockquote>
            <div class="meta">
                <div class="origin_author">
                    <a href="/v2/user" target="_blank">
                        郭璐Alu
                    </a>
                    <span>
                        评论自
                        <a href="/v2/detail" target="_blank">
                            集体宿舍，将3000万大学生拉入深渊
                        </a>
                    </span>
                </div>
            </div>
        </blockquote>
    </div>
</li> --}}
@endif
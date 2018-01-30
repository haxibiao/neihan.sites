{{-- 付费 --}}
{{-- 实名用户问题 --}}
{{-- <li class="article_item question have_img">
    <div class="pay_logo">
        <a href="#" target="_blank">
            <img src="/images/pay_question.png" data-toggle="tooltip" data-placement="top" title="付费问题" class="badge_icon_md"/>
        </a>
    </div>
    <div class="question_title">
        <a class="headline paper_title" href="" target="_blank">
            <span>孙尚香放技能是，应该怎么翻滚？</span>
        </a>
        <div class="question_info">
            <span>还剩 10分钟</span>
            <span class="question_follow_num">5 人已抢答</span>
            <span class="money">
                <i class="iconfont icon-jinqian1"></i>
                5元
            </span>
        </div>
    </div>
    <div class="question_answers">
        <a class="wrap_img" href="" target="_blank">
            <img src="/images/details_22.jpeg"/>
        </a>
        <div class="content">
            <div class="author">
                <a class="avatar" href="" target="_blank">
                    <img src="/images/photo_03.jpg"/>
                </a>
                <div class="info_meta">
                    <a href="" target="_blank" class="nickname">
                        尼策
                    </a>
                    <a href="" target="_blank">
                        <img src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="爱你城认证" class="badge_icon_xs"/>
                    </a>
                    <span class="time">
                        5分钟 前
                    </span>
                </div>
            </div>
            <p class="abstract">
                往要打英雄的左右翻滚?上下翻滚?提高普通攻击?打出更高的伤害?
                往要打英雄的左右翻滚?上下翻滚?提高普通攻击?打出更高的伤害?
                往要打英雄的左右翻滚?上下翻滚?提高普通攻击?打出更高的伤害?
            </p>
            <div class="meta">
                <span class="count">
                    20 分钟内抢答
                </span>
            </div>
        </div>
    </div>
</li> --}}


{{-- 问答文章摘要 --}}
@foreach ($questions as $question)
<li class="article_item question {{ $question->relateImage()?'have_img' :'' }}">
    @if($question->bonus >0)
    <div class="pay_logo">
        <a href="#" target="_blank">
            <img src="/images/pay_question.png" data-toggle="tooltip" data-placement="top" title="付费问题" class="badge_icon_md"/>
        </a>
    </div>
    @endif

    
    <div class="question_title">
        <a class="headline paper_title" href="/question/{{ $question->id }}" target="_blank">
            <span>{{ $question->title }}</span>
        </a>
    @if($question->bonus >0)
        <div class="question_info"> 
            @if($question->deadline)
            <span>还剩 {{ diffForHumansCN($question->deadline) }}</span>
            <span class="question_follow_num">{{ $question->count_answers }} 人已抢答</span>
            @else
             <span>抢答已经结束</span>
            @endif
            <span class="money">
                <i class="iconfont icon-jinqian1"></i>
                {{ $question->bonus }}元
            </span>
        </div>
    @else
        <div class="question_info">
            <span>{{ $question->count_answers }} 回答</span>
            <span class="question_follow_num">{{ $question->count_favorites }} 收藏</span>
        </div>
    @endif
    </div>
    <div class="question_answers">
       @if($question->relateImage())
        <a class="wrap_img" href="/question/{{ $question->id }}" target="_blank">
            <img src="{{ $question->relateImage()}}"/>
        </a>
       @endif

        <div class="content">
            <div class="author">
                @if($question->latestAnswer)
                <a class="avatar" href="/user/{{ $question->latestAnswer->user->id }}" target="_blank">
                    <img src="{{ $question->latestAnswer->user->avatar }}"/>
                </a>
                @elseif($question->is_anonymous)
                <a class="avatar" href="" target="_blank">
                    <img src="/images/photo_user.png"/>
                </a>
                @else
                <a class="avatar" href="/user/{{ $question->user->id }}" target="_blank">
                    <img src="{{ $question->user->avatar }}"/>
                </a>
                @endif
                <div class="info_meta">

                    @if($question->latestAnswer)
                    <a href="/user/{{ $question->latestAnswer->user->id }}" target="_blank" class="nickname">
                        {{ $question->latestAnswer->user->name }}
                    </a>
                    <a href="/question/{{ $question->id }}" target="_blank">
                        <img src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="爱你城认证" class="badge_icon_xs"/>
                    </a>
                    @elseif($question->is_anonymous)
                    <a href="javascript:;" target="_blank" class="nickname">
                        匿名用户
                    </a>
                    @else
                    <a href="/user/{{ $question->user->id }}" target="_blank" class="nickname">
                        {{ $question->user->name }}
                    </a>
                    <a href="/question/{{ $question->id }}" target="_blank">
                        <img src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="爱你城认证" class="badge_icon_xs"/>
                    </a>
                    @endif
                </div>
            </div>
                <p class="abstract">
           @if($question->latestAnswer)
                {{  strip_tags($question->latestAnswer->answer)  }}
           @else
                {{ $question->backgorund }}
           @endif
               </p>


            <div class="meta">
                <a href="/question" target="_blank" class="count count_link">
                    <i class="iconfont icon-liulan">
                    </i>
                    {{ $question->hits }}
                </a>
                <a href="/question" target="_blank" class="count count_link">
                    <i class="iconfont icon-svg37">
                    </i>
                    {{ $question->count_answers }}
                </a>
                <span class="count">
                    <i class="iconfont icon-03xihuan">
                    </i>
                    {{ $question->count_likes }}
                </span>
            </div>
        </div>
    </div>
      {{-- @if(Auth::user()->is_editor)
         <form action="{{ route('question.destroy', $question->id) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
             <button type="submit" class="btn btn-danger">删除</button>
        </form>
      @endif --}}
</li>
@endforeach


{{-- 问答文章摘要 --}}
@foreach ($questions as $question)
<li class="article_item question {{ question_is_closed($question) }} {{ $question->relateImage()?'have_img' :'' }}">
    {{-- pay_continue   pay_closed --}}

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
            <span>{{ $question->count_answers }} 回答</span>
            <span class="question_follow_num">{{ $question->count_favorites }} 收藏</span>
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
                    <span class="time">
                        {{ diffForHumansCN($question->created_at) }}
                    </span>
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

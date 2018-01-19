{{-- 问答文章摘要 --}}
@foreach ($questions as $question)
<li class="article_item question">
    <div class="question_title">
        <a class="headline paper_title" href="/question/{{ $question->id }}" target="_blank">
            <span>{{ $question->title }}</span>
        </a>
        <div class="question_info">
            <span>{{ $question->count_answers }} 回答</span>
            <span class="question_follow_num">{{ $question->count_favorites }} 收藏</span>
        </div>
    </div>
    <div class="question_answers">

        <a class="wrap_img" href="/detail" target="_blank">
            <img src="{{ $question->relateImage()}}"/>
        </a>

        <div class="content">
            <div class="author">
                <a class="avatar" href="/user/{{ $question->user->id }}" target="_blank">
                    <img src="{{ $question->user->avatar }}"/>
                </a>
                <div class="info_meta">
                    <a href="/user/{{ $question->user->id }}" target="_blank" class="nickname">
                        {{ $question->user->name }}
                    </a>
                    <a href="/detail" target="_blank">
                        <img src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="爱你城认证" class="badge_icon_xs"/>
                    </a>
                </div>
            </div>
            <p class="abstract">
                {{ $question->background }}
            </p>
            <div class="meta">
                <a href="/v2/detail" target="_blank" class="count count_link">
                    <i class="iconfont icon-liulan">
                    </i>
                    717
                </a>
                <a href="/detail" target="_blank" class="count count_link">
                    <i class="iconfont icon-svg37">
                    </i>
                    {{ $question->count_answers }}
                </a>
                <span class="count">
                    <i class="iconfont icon-03xihuan">
                    </i>
                    13
                </span>
                {{-- <a href="/detail" target="_blank" class="count count_link">
                    <i class="iconfont icon-fabulous">
                    </i>
                    {{ $question->hits }}
                </a> --}}
                {{-- <a href="/detail" target="_blank" class="count count_link">
                    <i class="iconfont icon-dianzan1">
                    </i>
                    {{ $question->count_favorites }}
                </a> --}}
                {{-- <a href="/detail" target="_blank" class="count count_link">
                    <i class="iconfont icon-xinxi2">
                    </i>
                    {{ $question->count_answers }}
                </a> --}}
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

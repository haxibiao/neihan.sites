@php
  $have_img = !empty($question->relateImage()) ? 'have-img' : '';
  $is_pay = $question->bonus > 0;
@endphp
{{-- 问答结束 pay + payed --}}
<li class="question-item {{ $have_img }} {{ $is_pay ? $question->closed ? 'payed pay':'pay' : '' }} " >
  <a class="title" target="_blank" href="/question/{{ $question->id }}">
      <span>{{ $question->title }}</span>
  </a>
  <div class="question-info descriptor">
    <span class="question-answer-num">{{ $question->count_answers }}回答</span> · <span class="question-follow-num">{{ $question->count_favorites }}人收藏</span>
     @if($is_pay)
    <span class="amount pull-right"><i class="iconfont icon-jinqian1"></i>{{ $question->bonus }}元</span>
     @endif
  </div>
  <div class="question-warp">

    <div class="content">
      @if($question->latestAnswer)
      <div class="author hidden-xs">
        @if($question->is_anonymous)
          匿名用户
          <span class="time">{{ $question->createdAt() }}</span>
        @else
        <a class="avatar" target="_blank" href="/user/{{ $question->latestAnswer->user_id }}">
          <img src="{{ $question->latestAnswer->user->avatar() }}" alt="">
        </a> 
        <div class="info">
          <a class="nickname" target="_blank" href="/user/{{ $question->latestAnswer->user_id }}">{{ $question->latestAnswer->user->name }}</a>
          <img class="badge-icon" src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}认证" alt="">
          <span class="time">{{ $question->createdAt() }}</span>
        </div>
        @endif
      </div>
      @endif
      <p class="abstract">
        @if($question->latestAnswer)
            {{ $question->latestAnswer->shortText() }}
        @else 
          {{ strip_tags($question->background) }}
        @endif
      </p>
      <div class="meta">
        <a target="_blank" href="/question/{{ $question->id }}">
          <i class="iconfont icon-liulan"></i> {{ $question->hits }}
        </a>        
        <a target="_blank" href="/question/{{ $question->id }}">
          <i class="iconfont icon-svg37"></i> {{ $question->count_comments }}
        </a>      
        <a target="_blank" href="/question/{{ $question->id }}"><i class="iconfont icon-03xihuan"></i> {{ $question->count_likes }}</a>
        {{-- <span><i class="iconfont icon-qianqianqian"></i> 2</span> --}}
      </div>
    </div>
    
    @if(!empty($question->relateImage()))
      <a class="wrap-img" href="/question/{{ $question->id }}" target="_blank">
          <img src="{{ $question->relateImage() }}" alt="">
      </a>
    @endif

    
  </div>
</li>

@php
  $have_img = !empty($question->relateImage()) ? 'have-img' : '';
@endphp
<li class="question-item simple {{ $have_img }}">
  <a class="title" target="_blank" href="/question/{{ $question->id }}">
      <span>{{ $question->title }}</span>
  </a>

  @if($question->latestAnswer)
  <div class="author hidden-sm">
    <a class="avatar" target="_blank" href="/user/{{ $question->latestAnswer->user_id }}">
      <img src="{{ $question->latestAnswer->user->avatar() }}" alt="">
    </a> 
    <div class="info">
      <a class="nickname" target="_blank" href="/user/{{ $question->latestAnswer->user_id }}">{{ $question->latestAnswer->user->name }}</a>
      <img class="badge-icon" src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}认证" alt="">
      <span class="time" data-shared-at="2017-11-06T09:20:28+08:00">知名自媒体人</span>
    </div>
    <div class="pull-right">{{ $question->count_likes }}赞</div>
  </div>
  @endif

  <div class="question-warp">    
    @if(!empty($question->relateImage()))
      <a class="wrap-img" href="/question/{{ $question->id }}" target="_blank">
          <img src="{{ $question->relateImage() }}" alt="">
      </a>
    @endif
    <div class="content">
      <p class="abstract">
        @if($question->latestAnswer)
            {{ $question->latestAnswer->shortText() }}
        @else 
          {{ $question->background }}
        @endif
      </p>
    </div>
  </div>
</li>

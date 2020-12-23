@php
  $have_img = !empty($question->relateImage()) ? 'have-img' : '';
  $latestResolution = data_get($question,'latestResolution',null);

@endphp
<li class="question-item simple {{ $have_img }}">
  <a class="title"   href="/question/{{ $question->id }}">
      <span>{{ $question->title }}</span>
  </a>
  @if($latestResolution)
  <div class="author hidden-sm">
    <a class="avatar"   href="/user/{{ data_get($latestResolution,'user_id') }}">
      <img src="{{ data_get($latestResolution,'user.avatarUrl') }}" alt="">
    </a>
    <div class="info">
      <a class="nickname"   href="/user/{{ data_get($latestResolution,'user_id')}}">{{ data_get($latestResolution,'user.name')}}</a>
      <img class="badge-icon" src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="{{ seo_site_name() }}认证" alt="">
      <span class="time" data-shared-at="2017-11-06T09:20:28+08:00">知名自媒体人</span>
    </div>
    <div class="pull-right">{{ data_get($latestResolution,'count_likes',0) }}赞</div>
  </div>
  @endif

  <div class="question-warp">
    <div class="content">
      <p class="abstract">
        @if($latestResolution)
            {{ $latestResolution->shortText() }}
        @else
          {!! $question->background !!}
        @endif
      </p>
    </div>
    @if(!empty($question->relateImage()))
      <a class="wrap-img" href="/question/{{ $question->id }}"  >
          <img src="{{ $question->relateImage() }}" alt="">
      </a>
    @endif
  </div>
</li>

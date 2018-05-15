<div class="follow-card">
  <div class="note-info">
      <a class="avatar" href="/user/{{ $article->user->id }}">
        <img src="{{ $article->user->avatar() }}" alt="">
      </a>

      @weixin
      <p>
        喜欢作者? 长按下面的微信二维码关注
      </p>
      <p>
        <img src="/qrcode/{{ get_domain() }}.jpg" alt="喜欢作者? 长按下面的微信二维码关注">
      </p>
      @else
        @if(!$article->isSelf())
          <follow
            type="user"
            id="{{ $article->user->id }}"
            user-id="{{ user_id() }}"
            followed="{{ is_follow('users', $article->user->id)}}">
          </follow>
        @endif
      @endweixin

      <div class="title">
        <a class="name" href="javascript:;">{{ $article->user->name }}</a>
      </div>
      <div class="info">
        <p>写了 {{ $article->user->count_words }} 字，被 {{ $article->user->count_follows }} 人关注，获得了 {{ $article->user->count_likes }} 个喜欢</p>
      </div>
    </div>
    <p class="signature">
      {{ $article->user->introduction() }}
    </p>
</div>
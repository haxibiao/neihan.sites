<li class="col-xs-6 col-md-4 video">
  <div class="video-item vt">
    <div class="thumb">
      <a href="{{ $article->content_url() }}" target="_blank">
        <img src="{{ $article->cover() }}" alt="{{ $article->title }}">
        <i class="duration">
          {{-- 持续时间 --}}  
          @sectominute($article->video->duration)
        </i>
      </a>
    </div>
    <ul class="info-list">
      <li class="video-title">
        <a href="{{ $article->content_url() }}">{{ $article->title }}</a>
      </li>
      <li>
        {{-- 播放量 --}}
        <p class="subtitle single-line">{{ $article->hits }}次播放</p>
      </li>
    </ul>
  </div>
</li>
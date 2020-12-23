<li class="col-xs-6 col-md-4 video">
  <div class="video-item vt">
    <div class="thumb" style="max-height: 120px; overflow-y: hidden;">
      <a href="{{ $article->url }}"  >
        <img src="{{ $article->cover }}" alt="{{ $article->subject }}">
        <i class="duration">
          {{-- 持续时间 --}}  
          @sectominute($article->video->duration)
        </i>
      </a>
    </div>
    <ul class="info-list">
      <li class="video-title">
        <a href="{{ $article->url }}"  >{{ $article->subject }}</a>
      </li>
      <li>
        {{-- 播放量 --}}
        <p class="subtitle single-line">{{ $article->hits }}次播放</p>
      </li>
    </ul>
  </div>
</li>
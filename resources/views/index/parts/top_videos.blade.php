<div class="row videos distance">
  <div class="vd-head">
      <h3 class="vd-title">
          <span class="title-icon">
              <i class="iconfont icon-huo"></i>视频剪辑
          </span>
      </h3>
      <a href="/video" class="more-video" title="{{ seo_site_name() }}视频剪辑">
          <p>
              更多
              <i class="iconfont icon-youbian"></i>
          </p>
      </a>
  </div>
  @foreach($videoPosts as $post)
    <div class="col-xs-6 col-md-3 video">
      <div class="video-item vt">
        <div class="thumb">
          <a href="/video/{{$post->video->id}}"  >
            @if(!empty($post->cover))
            <img src="{{ $post->cover}}" alt="{{ $post->content }}">
            @endif
            <i class="duration">
              @sectominute($post->video->duration)
            </i>
            <i class="hover-play"> </i>
          </a>
        </div>
        <ul class="info-list">
          <li class="video-title">
            <a   href="/video/{{$post->video->id}}">{{ $post->content}}</a>
          </li>
          <li>
            <p class="subtitle single-line">{{ random_int(1000,9999) }}次播放</p>
          </li>
        </ul>
      </div>
    </div>
  @endforeach
</div>

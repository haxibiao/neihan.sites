{{-- 用户信息 --}}
<div class="information">
  <a class="avatar" href="/user/{{ $user->id }}">
    <img src="{{ $user->avatar() }}" alt="">
  </a>        
  
  <a class="button btn_followed"><span><i class="gougou iconfont icon-weibiaoti12"></i><i class="chacha iconfont icon-cha"></i></span></a>

  <a class="button btn_hollow" href="/chat/with/{{ $user->id }}">发消息</a>

  <div class="title">
    <a class="name" href="/user/{{ $user->id }}">{{ $user->name }}</a>
    <i class="man iconfont icon-nansheng1"></i>
  </div>
  <div class="info">
    <ul class="info_list">
      <li>
        <a href="/user/{{ $user->id }}/followings">
          <p>{{ $user->count_followings }}</p>
          关注
          <i class="iconfont icon-youbian"></i>
        </a>
      </li>
      <li>
        <a href="/user/{{ $user->id }}/followers">
          <p>{{ $user->count_follows }}</p>
          粉丝
          <i class="iconfont icon-youbian"></i>
        </a>
      </li>
      <li>
        <a href="/user/{{ $user->id }}">
          <p>{{ $user->count_articles }}</p>
          文章
          <i class="iconfont icon-youbian"></i>
        </a>
      </li>
      <li>
        <a href="javascript:;">
          <p>{{ $user->count_words }}</p>
          字数
          <i class="iconfont icon-youbian"></i>
        </a>
      </li>
      <li>
        <a href="javascript:;">
          <p>{{ $user->count_likes }}</p>
          收获喜欢
          <i class="iconfont icon-youbian"></i>
        </a>
      </li>
    </ul>
  </div>
</div>
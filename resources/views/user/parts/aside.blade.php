<div class="aside col-sm-4 col-sm-offset-1 hidden-xs">
    @if($user->is_editor)
      <ul class="icon-text-list distance">
          <li><a href="javascript:;"><img class="badge-icon" src="/images/signed.png" alt=""></i>{{ config('app.name') }}签约作者</a></li>
          <li><a href="javascript:;"><img class="badge-icon" src="/images/excellence.png" alt=""></i>{{ config('app.name') }}优秀作者</a></li>
      </ul>
    @endif
    
    @include('user.aside.introduction')

    <ul class="icon-text-list icon-fix distance">
      <li><a href="/user/{{ $user->id }}/followed-categories"><i class="iconfont icon-duoxuan"></i> <span>{{ $user->ta() }}关注的专题</span></a></li>
      <li><a href="/user/{{ $user->id }}/followed-collections"><i class="iconfont icon-wenji"></i> <span>{{ $user->ta() }}关注的文集</span></a></li>
      <li><a href="/user/{{ $user->id }}/likes"><i class="iconfont icon-xin"></i> <span>{{ $user->ta() }}喜欢的文章</span></a></li>
    </ul>

    @include('user.aside.created_categories')
    @include('user.aside.managed_categories')
    @include('user.aside.collections')
    
    <p class="plate-title"><a href="javascript:;">加入黑名单</a> · <a href="javascript:;">举报用户</a></p>
</div>
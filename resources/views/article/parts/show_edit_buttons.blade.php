@if(!is_in_app() && !Agent::match('micromessenger'))
<p class="pull-right">
    @if(checkEditor())
      @if(Request::get('weixin'))
        <a href="/article/{{ $article->id }}" class="btn btn-danger">返回正常模式</a>
      @else
        <a href="/article/{{ $article->id }}?weixin=1" class="btn btn-success">微信发布模式</a>
      @endif
    @endif
    @if(Auth::check() && ($article->isSelf() || Auth::user()->is_admin ))
      <a href="/article/{{ $article->id }}/edit" class="btn btn-danger">编辑文章</a>
    @endif
</p>
@endif
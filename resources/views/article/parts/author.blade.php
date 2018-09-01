  <div class="article-author">
    <div class="user-info">
      <a class="avatar" href="/user/{{ $article->user->id }}">
        <img src="{{ $article->user->avatar() }}" alt="">
      </a>
      <div class="title">
        <a class="nickname" href="/user/{{ $article->user->id }}">{{ $article->user->name }}</a>
        <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="{{ config('app.name') }}签约作者" alt="">
        {{-- <a class="btn-base btn-follow btn-xs"><i class="iconfont icon-jia1"></i>关注</a> --}}
        @if(!$article->isSelf())
          <follow
            type="user"
            size-class="btn-xs"
            id="{{ $article->user->id }}"
            user-id="{{ user_id() }}"
            followed="{{ is_follow('users', $article->user->id) }}">
          </follow>
        @endif

        <!-- 自己或者编辑权限以上的才可以有编辑按钮 -->
        @if(checkEditor())
          {!! Form::open(['method' => 'delete', 'route' => ['article.destroy', $article->id]]) !!}
            @if($article->status == -1)
              {!! Form::hidden('restore', 'yes') !!}
            @endif
              {!! Form::submit($article->status == -1 ? "恢复文章":"丢入回收站", ['class' => 'btn-base btn-sm btn-light btn-danger']) !!}                
              {!! Form::close() !!}
              @if($article->type == 'article')
                <a class="btn-base btn-light btn-sm" href="/article/{{ $article->id }}/edit">编辑文章</a>
              @endif
        @elseif($article->isSelf())
          @if($article->type == 'article')
            {{-- 自己的文章才允许编辑，动态目前只可以删除 --}}
            <a class="btn-base btn-light btn-sm" href="/write#/notebooks/{{ $article->collection_id }}/notes/{{ $article->id }}">编辑文章</a>
          @else 
            {!! Form::open(['method' => 'delete', 'route' => ['article.destroy', $article->id]]) !!}              
              @if($article->status == -1)
                {!! Form::hidden('restore', 'yes') !!}
              @endif
                {!! Form::submit($article->status == -1 ? "恢复动态":"删除动态", ['class' => 'btn-base btn-sm btn-light btn-danger']) !!}
                {!! Form::close() !!}
              @endif
          @endif
      </div>
      <!-- 文章数据信息 -->
      <div class="info">
         <span class="publish-time">@timeago($article->created_at)</span>
          <span class="wordage hidden-xs">字数 {{ $article->count_words }}</span>
          <span class="views-count">阅读 {{ $article->hits }}</span>
          <span class="comments-count">评论 {{ $article->count_replies }}</span>
          <span class="likes-count">喜欢 {{ $article->count_likes }}</span>
          <span class="rewards-count hidden-xs">赞赏 {{ $article->count_tips }}</span>
      </div>
    </div>
  </div>
@extends('layouts.app')

@section('title')
    推荐作者 - 爱你城
@stop
@section('content')
<div id="hot_users">
    <div class="container">
        <div class="recommend">
            <div class="recommend_img">
                <img src="/images/recommend_users.png"/>
                <a class="help" href="javascript:;" target="_blank">
                    <i class="iconfont icon-bangzhu">
                    </i>
                    如何成为签约作者
                </a>
            </div>
            <div class="row">
              @foreach($users as $user)
                <div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
                    <div class="collection_wrap">
                        <a href="/user/{{ $user->id }}" target="_blank">
                            <img class="avatar_lg" src="{{ $user->avatar }}"/>
                            <h4 class="headline">
                                {{ $user->name }}
                            </h4>
                            <p class="abstract">
                                {{ $user->introduction }}
                            </p>
                        </a>

                        <follow followed="{{ Auth::user()->isFollow('users', $user->id)}}" id="{{ $user->id }}" type="users" user-id="{{ Auth::user()->id }}">
                        </follow>

                        <hr/>
                        <div class="recent">最新更新</div>
                        <div class="count recent_update">
                            @foreach($user->newArticle() as $article)
                            <a href="/article/{{ $article->id }}" class="new" target="_blank">{{ $article->title }}</a>
                            @endforeach
                            @if($user->newArticle()->count()==0)
                              <a href="javascript:;" class="new" target="_blank">这位用户还暂时没有新的文章哦</a>
                            @endif
                        </div>
                    </div>
                </div>
              @endforeach
            </div>
        </div>
    </div>
</div>
@stop

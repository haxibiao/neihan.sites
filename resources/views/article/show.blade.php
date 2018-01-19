@extends('layouts.app')

@section('title')
    {{ $article->title }} - 爱你城
@stop

@section('description')
    {{ $article->description() }}
@stop
@section('content')
<div id="detail">
    <div class="note">
        <div class="container">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="article">
                    <h1 class="title">
                        {{ $article->title }}
                    </h1>
                    <div class="author">
                        <a class="avatar" href="#">
                            <img src="{{ $article->user->avatar }}"/>
                        </a>
                        <div class="info">
                            <span class="nickname">
                                <a href="#">
                                    {{ $article->user->name }}
                                </a>
                            </span>
                                  <follow
                                    type="users"
                                    id="{{ $article->user->id }}"
                                    user-id="{{ Auth::check() ? Auth::user()->id : false }}"
                                    followed="{{ Auth::check() ? Auth::user()->isFollow('user', $article->user->id) : false }}"
                                    is_small="btn_follow_xs"
                                    is_small_followed="btn_followed_xs">
                                  </follow>
                            <div class="meta">
                                <span>
                                    {{ diffForHumansCN($article->created_at) }}
                                </span>
                                <span>
                                    字数 {{ $article->words }}
                                </span>
                                <span>
                                    阅读 {{ $article->hits }}
                                </span>
                                <span>
                                    评论 {{ $article->count_replies }}
                                </span>
                                <span>
                                    喜欢 {{ $article->count_favorites }}
                                </span>
                            </div>
                        </div>
                        @if(Auth::check() && Auth::user()->is_editor)
                           <a href="/article/{{ $article->id }}/edit" target="_blank" class="btn_base btn_edit pull-right">编辑文章</a>
                        @endif
                    </div>

                    @include('article.parts.article_body')
                    <div class="article_foot">
                        <a class="notebook" href="#">
                            <i class="iconfont icon-wenji">
                            </i>
                            <span>
                                日记本
                            </span>
                        </a>
                        <div class="copyright">
                            © 著作权归作者所有
                        </div>

                    </div>
                </div>
                <div class="follow_detail">
                    <div class="author">
                        <a class="avatar avatar_sm" href="/user/{{ $article->user->id  }}" target="_blank">
                            <img src="{{ $article->user->avatar }}"/>
                        </a>
                                  <follow
                                    type="users"
                                    id="{{ $article->user->id }}"
                                    user-id="{{ Auth::check() ? Auth::user()->id : false }}"
                                    followed="{{ Auth::check() ? Auth::user()->isFollow('user', $article->user->id) : false }}">
                                  </follow>
                        <div class="info_meta">
                            <a class="nickname" href="/user/{{ $article->user->id }}" target="_blank">
                                {{ str_limit($article->user->name) }}
                            </a>
                            <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_sm" />
                            <div class="meta">
                                写了 {{ $article->user->count_words }} 字，被 {{ $article->user->count_follows }} 人关注，获得了 {{ $article->user->count_likes }} 个喜欢
                            </div>
                        </div>
                    </div>
                    <div class="signature">
                        {{ $article->user->introduction }}
                    </div>

                </div>
                <div class="support_author">
                  @if(Auth::user()->is_tips)
                    <p>
                        如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！
                    </p>
                     <div class="btn_base btn_pay" data-target="#support_modal" data-toggle="modal">
                        赞赏支持
                    </div>
                    <div>
                        <ul class="collection_follower">
                         @foreach([1,2,3,4,5,6,7,8] as $f)
                            <li>
                                <a class="avatar" href="javascript:;">
                                    <img src="/images/photo_0{{ rand(2,3) }}.jpg"/>
                                </a>
                            </li>
                         @endforeach
                        </ul>
                        <span class="rewad_user">
                            等10人
                        </span>
                        <support-modal user-id={{ $article->user->id }} article-id={{ $article->id }}></support-modal>
                    </div>
                  @endif
                </div>
                <div class="meta_bottom">
                    <like id="{{ $article->id }}" type="article" is-login="{{ Auth::check() }}" article-likes="{{ $article->count_likes }}"></like>
                    <div class="share_group">
                        <a class="share_circle" href="#" data-placement="top" data-container="body" data-toggle="tooltip" data-trigger="hover" data-original-title="分享到微信">
                            <i class="iconfont icon-weixin1">
                            </i>
                        </a>
                        <a class="share_circle" href="#" data-placement="top" data-container="body" data-toggle="tooltip" data-trigger="hover" data-original-title="分享到微博">
                            <i class="iconfont icon-sina">
                            </i>
                        </a>
                        <a class="share_circle" href="#" data-placement="top" data-container="body" data-toggle="tooltip" data-trigger="hover" data-original-title="下载长微博图片">
                            <i class="iconfont icon-zhaopian">
                            </i>
                        </a>
                    </div>
                </div>
                <div>
                     <comments type="articles" id="{{ $article->id }}" is-login="{{ Auth::check() }}"></comments>
                </div>
            </div>
        </div>
    </div>
    @include('article.parts._show_category')

</div>
@stop

@push('side_tools')
    <article-tool id="{{ $article->id }}"></article-tool>
@endpush

@push('modals')
   <detailmodal-user article-id="{{ $article->id }}"></detailmodal-user>
   <detailmodal-home article-id="{{ $article->id }}"></detailmodal-home>
@endpush

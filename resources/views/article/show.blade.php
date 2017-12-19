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
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="article">
                    <h1 class="title">
                        {{ $article->title }}
                    </h1>
                    <div class="author">
                        <a class="avatar" href="#">
                            <img src="{{ $article->user->avatar }}"/>
                        </a>
                        <div class="info">
                            <span class="name">
                                <a href="#">
                                    {{ $article->user->name }}
                                </a>
                            </span>
                                  <follow 
                                    type="users" 
                                    id="{{ $article->user->id }}" 
                                    user-id="{{ Auth::check() ? Auth::user()->id : false }}" 
                                    followed="{{ Auth::check() ? Auth::user()->isFollow('user', $article->user->id) : false }}">
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
                                    喜欢 {{ $article->count_favoites }}
                                </span>
                            </div>
                        </div>
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
                   {{--      <div class="modal_wrap">
                            <a href="#">
                                举报文章
                            </a>
                        </div> --}}
                    </div>
                </div>
                <div class="follow_detail">
                    <div class="info">
                        <a class="avatar" href="/user/{{ $article->user->id  }}" target="_blank">
                            <img src="{{ $article->user->avatar }}"/>
                        </a>
                                  <follow 
                                    type="users" 
                                    id="{{ $article->user->id }}" 
                                    user-id="{{ Auth::check() ? Auth::user()->id : false }}" 
                                    followed="{{ Auth::check() ? Auth::user()->isFollow('user', $article->user->id) : false }}">
                                  </follow>
                        <a class="title" href="/v1/user" target="_blank">
                            {{ str_limit($article->user->name) }}
                        </a>
                        <p>
                            写了 280343 字，被 {{ $article->user->count_favorites }} 人关注，获得了 {{ $article->user->count_likes }} 个喜欢
                        </p>
                    </div>
                    <div class="signature">
                        {{ $article->user->introduction }}
                    </div>
                </div>
                <div class="support_author">
                    <p>
                        如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！
                    </p>
                    <div class="btn_pay">
                        赞赏支持
                    </div>
                    <div class="supporter">
                        <ul class="support_list">
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_03.jpg"/>
                                </a>
                            </li>
                            <li>
                                <a class="avatar" href="/v1/user">
                                    <img src="/images/photo_02.jpg"/>
                                </a>
                            </li>
                        </ul>
                        <span class="rewad_user">
                            等10人
                        </span>
                    </div>
                </div>
                <div class="meta_bottom">
{{--                     <div class="like">
                        <div class="like_group">
                            <div class="btn_like">
                                <a href="#">
                                    <i class="iconfont icon-xin">
                                    </i>
                                    喜欢
                                </a>
                            </div>
                            <div class="modal_wrap">
                                <a href="#">
                                    13
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    <like id="{{ $article->id }}" type="article" is-login="{{ Auth::check() }}" article-likes="{{ $article->count_likes }}" />
                    <div class="share_group">
                        <a class="share_circle" href="#">
                            <i class="iconfont icon-weixin1">
                            </i>
                        </a>
                        <a class="share_circle" href="#">
                            <i class="iconfont icon-sina">
                            </i>
                        </a>
                        <a class="share_circle" href="#">
                            <i class="iconfont icon-zhaopian">
                            </i>
                        </a>
                        <a class="share_circle more_share" href="#">
                            更多分享
                        </a>
                    </div>
                </div>
                     <comments type="articles" id="{{ $article->id }}" is-login="{{ Auth::check() }}"></comments>
            </div>
        </div>
    </div>
    <div class="note_bottom">
        <div class="container">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div>
                    <div class="main">
                        <div class="title">
                            被以下专题收入，发现更多相似内容
                        </div>
                        <div class="include_collection">
                            <a class="item" href="javascript:;">
                                <div class="name">
                                    ＋ 收入我的主题
                                </div>
                            </a>
                          @foreach($article->categories as $category)
                            <a class="item" href="/{{ $category->name_en }}">
                                <img src="{{ $category->logo }}">
                                <div class="name">
                                    {{ $category->name }}
                                </div>
                            </a>
                          @endforeach
                        </div>
                    </div>
                </div>
                <div>
                    <div class="recommend_note">
                        <div class="meta">
                            <div class="title">
                                推荐阅读
                                <a href="javascript:;">
                                    更多精彩内容
                                    <i class="iconfont icon-youbian">
                                    </i>
                                </a>
                            </div>
                        </div>
                        @include('parts.list.article_list_category',['articles'=>$data['related']])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

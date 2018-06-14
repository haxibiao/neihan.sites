@extends('layouts.app')
@section('title')
    收藏的文章 - {{ env('APP_NAME') }}
@stop
@section('content')
        <div id="bookmarks">
            <div class="main center">
                <div class="page-banner">
                    {{-- <img src="/images/collect-note.png" alt=""> --}}
                    <div class="banner-img collect-note">
                        <div>
                            <i class="iconfont icon-biaoqian"></i>
                            <span>收藏的文章</span>
                        </div>
                    </div>
                </div>
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#articles" aria-controls="articles" role="tab" data-toggle="tab">收藏的文章</a>
                        </li>
                        <li role="presentation">
                            <a href="#questions_tab" aria-controls="questions_tab" role="tab" data-toggle="tab">收藏的问题</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="fade in tab-pane active" id="articles">
                            <ul class="article-list">
                                @foreach($data['articles'] as $fav)
                                    @include('parts.article_item', ['article' => $fav->faved])
                                @endforeach
                            </ul>
                        </div>
                        <div role="tabpanel" class="fade tab-pane" id="questions_tab">
                            <div>
                                @foreach($data['questions'] as $fav)
                                    @include('question.parts.question_item', ['question' => $fav->faved])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop
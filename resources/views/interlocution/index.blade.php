@extends('layouts.app')

@section('title')
    问答 - 爱你城
@stop
@section('content')
<div id="interlocution">
    <div class="interlocution_top">
        <div class="container clearfix">
            {{-- 专题分类 --}}
            @include('interlocution.parts.category_list',['categories'=>$categories])
        </div>
    </div>
    <div class="interlocution_note">
        <div class="container">
            {{-- 左侧 --}}
            <div class="main col-xs-12 col-sm-8">
                {{-- 文章摘要 --}}
                <div class="article_list">
                    @include('interlocution.parts.article_list',['questions'=>$questions])
                </div>
                {{-- 阅读更多文章摘要 --}}
            </div>
            {{-- 右侧 --}}
            <div class="aside col-sm-4">
                {{-- 轮播图 --}}
                @include('interlocution.parts.poster')
                {{-- 精选回答 --}}
                @include('interlocution.parts.selected_article_list')
                {{-- 联系我们 --}}
                @include('interlocution.parts.contact_us')
            </div>
        </div>
        @include('parts.foot')
    </div>
    <question-modal></question-modal>
</div>
@stop

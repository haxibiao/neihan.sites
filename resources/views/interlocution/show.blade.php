@extends('layouts.app')

@section('title')
    {{ $question->title }} - 爱你城问答
@stop
@section('content')
<div id="interlocution_details">
    <div class="note">
        <div class="container">
            <div class="row">
                {{-- 左侧 --}}
                <div class="main col-xs-12 col-sm-8">
                    {{-- 问答详情 --}}
                    @include('interlocution.parts.question_single')
                </div>
                {{-- 右侧 --}}
                <div class="aside col-sm-4">
                    {{-- 轮播图 --}}
                    @include('interlocution.parts.poster')
                    {{-- 二维码 --}}
                    @include('interlocution.parts.scan_code')
                    {{-- 猜你喜欢 --}}
                    @include('interlocution.parts.like_answer')
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="note_bottom">
        <div class="note_title">
            <div class="container">
                <div class="col-xs-12 col-sm-8">
                    <div class="litter_title title_line">
                        <span class="title_active">
                            更多阅读
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="note_recommend">
            <div class="container">
                <div class="col-xs-12 col-sm-8">
                    <div class="recommend_note">
                        <div class="article_list">
                            @include('interlocution.parts.article_list')
                        </div>
                        <a class="load_more" href="javascript:;">
                            阅读更多
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@include('parts.foot')
@stop

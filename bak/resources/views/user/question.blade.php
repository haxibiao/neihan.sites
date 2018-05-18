@extends('layouts.app')

@section('title')
     我的问答
@stop
@section('content')
<div id="bookmark">
    <div class="container">
        <div class="page_banner">
            <div class="banner_img question_note">
                <div>
                    <i class="iconfont icon-help"></i>
                    <span>收藏的问答</span>
                </div>
            </div>
        </div>
        @include('interlocution.parts.article_list',['questions'=>$questions])
    </div>
    @stop
</div>
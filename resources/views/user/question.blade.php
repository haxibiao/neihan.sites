@extends('layouts.app')

@section('title')
  	 我的提问
@stop
@section('content')
<div id="hot_articles">
    <div class="container">
        <div class="row">
            {{-- 左侧 --}}
            <div class="main col-xs-12 col-sm-12">
            	<img src="/images/board_02.png" class="tag_banner" />
                {{-- 文章摘要 --}}
               @include('interlocution.parts.article_list',['questions'=>$questions])
            </div>
            
    </div>
</div>
@stop

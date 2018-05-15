@extends('layouts.app')

@section('title') {{ $category->name }} - {{ env('APP_NAME') }} @endsection
@section('keywords') {{ $category->name }} @endsection
@section('description') {{ $category->description?$category->description:config('seo.description') }} @endsection

@section('content')
<div id="category">
    <div class="clearfix">
        <div class="main col-sm-7">
            {{-- 分类信息 --}}
            @include('category.parts.information')
            {{-- 内容 --}}
            <div class="content">
                 <!-- Nav tabs -->
                 <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                   <li role="presentation" class="active">
                    <a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>最新评论</a>
                   </li>
                   <li role="presentation">
                    <a href="#include" aria-controls="include" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>最新收录</a>
                   </li>
                   <li role="presentation">
                    <a href="#hot" aria-controls="hot" role="tab" data-toggle="tab"><i class="iconfont icon-huo"></i>热门</a>
                   </li>
                 </ul>
                 <!-- Tab panes -->
                 <div class="article-list tab-content">
                   <ul role="tabpanel" class="fade in note_list tab-pane active" id="comment">                                     
                       @each('parts.article_item', $data['commented'], 'article')
                       @if(!Auth::check())
                       <div>{!! $data['commented']->links() !!}</div>
                       @else
                       <article-list api="/{{ $category->name_en }}?commented=1" start-page="2" />
                      @endif
                   </ul>
                   <ul role="tabpanel" class="fade note_list tab-pane" id="include">                                      
                       @each('parts.article_item', $data['collected'], 'article')
                       @if(!Auth::check())
                       <div>{!! $data['collected']->links() !!}</div>
                       @else
                       <article-list api="/{{ $category->name_en }}?collected=1" start-page="2" />
                       @endif
                   </ul>
                   <ul role="tabpanel" class="fade note_list tab-pane" id="hot">                                      
                       @each('parts.article_item', $data['hot'], 'article')
                       @if(!Auth::check())
                       <div>{!! $data['hot']->links() !!}</div>
                       @else
                       <article-list api="/{{ $category->name_en }}?hot=1" start-page="2" />
                       @endif
                   </ul>
                 </div>
            </div>
        </div>
        <div class="aside col-sm-4 col-sm-offset-1">
            @include('category.parts.description')
            @include('parts.share')
            @include('category.parts.admins')
            @include('category.parts.authors')
            @include('category.parts.followers')
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title')
    编辑文章
@endsection

@section('content')
<div class="container">
<div class="row">
  <div class="col-md-offset-1 col-md-10">
    {!! Form::open(['method' => 'PUT', 'route' => ['article.update', $article->id], 'class' => 'form-horizontal', 'id'=>'article_form', 'enctype' => "multipart/form-data"]) !!}
      <div class="row">
          <legend>编辑文章</legend>
    
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {!! Form::label('title', '标题') !!}
            {!! Form::text('title',$article->title, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('title') }}</small>
        </div>
        
        @editor
        <div class="row">
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('delay') ? ' has-error' : '' }}">
                   {!! Form::label('delay', '定时发布') !!}
                   {!! Form::select('delay',[
                        0 => '立即',
                        1 => '1天后',
                        2 => '2天后',
                        3 => '3天后',
                        4 => '4天后',
                        5 => '5天后',
                        6 => '6天后',
                        7 => '7天后',
                        8 => '8天后',
                        9 => '9天后',
                        10 => '10天后',
                    ], null, ['id' => 'delay', 'class' => 'form-control', 'disabled' => $article->delay_time ? true : false]) !!}
                    @if($article->delay_time)
                        <p class="small text-info">已启动定时发布于: ({{ diffForHumansCN($article->delay_time) }})</p>
                    @endif
                   <small class="text-danger">{{ $errors->first('delay') }}</small>
                </div>
            </div>             
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('is_top') ? ' has-error' : '' }}">
                    {!! Form::label('is_top', '是否上首页') !!}
                    {!! Form::select('is_top', [ 0 => '否', 1 => '是'], $article->is_top, ['id' => 'is_top', 'class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">{{ $errors->first('is_top') }}</small>
                </div>
            </div>
        </div>

        <div class="form-group{{ $errors->has('keywords') ? ' has-error' : '' }}">
            {!! Form::label('keywords', '关键词(用英文,隔开 或者按Tab键自动隔开关键词)') !!}
            {{-- {!! Form::text('keywords', str_replace(' ',',',$article->keywords), ['class' => 'form-control', 'required' => 'required', 'data-role' => 'tagsinput']) !!} --}}
            <tags-input name="keywords" value="{{ $article->keywords }}"></tags-input>
            <small class="text-danger">{{ $errors->first('keywords') }}</small>
        </div>
        @endeditor

       {{--  <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', '简介') !!}
            {!! Form::textarea('description',$article->description, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('description') }}</small>
        </div> --}}

        <div class="article form-group{{ $errors->has('body') ? ' has-error-for-editor' : '' }}">
            {!! Form::label('body', '正文') !!}
            <editor name="body" value="{{ $article->body }}"　autosave></editor>
            @push('css')
                <link rel="stylesheet" type="text/css" href="/css/e.css">
            @endpush
            <small class="text-danger">{{ $errors->first('body') }}</small>
        </div>
        
        {{-- 选择配图 --}}
        {{-- @include('article.parts.images_selected', ['images' => $article->images, 'article' => $article]) --}}
        {{-- 选择配图 vue --}}
        @php
           $img_urls = [];
           foreach($article->images as $image) {
            $img_urls[] = $image->url();
           } 
        @endphp 
        <image-select :img-urls="{{ json_encode($img_urls) }}"></image-select>

        @editor
        <div class="row">            
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('category_ids') ? ' has-error' : '' }}">
                    {!! Form::label('category_ids', '专题') !!}
                    <category-select categories="{{ json_encode($article->categories->pluck('name','id') ) }}"></category-select>
                    <small class="text-danger">{{ $errors->first('category_ids') }}</small>
                </div>
            </div>
        </div>
        @endeditor
    
        <div class="btn-group-lg pull-right">
            {{-- <input type="hidden" name="user_id" value="{{ Auth::user()->id }}"> --}}
            <input type="hidden" name="image_url" value="{{ $article->image_url }}">
            <input type="hidden" name="status" id="hidden_status" value="1">
            {!! Form::button("存稿", ['class' => 'btn btn-warning btn-draft', 'id'=>'draftBtn']) !!}
            {!! Form::submit("发布", ['class' => 'btn btn-success']) !!}
        </div>
    
      </div>
    {!! Form::close() !!}
    <modal-images></modal-images>
  </div>
</div>

</div>

@endsection

@push('scripts')
   @include('article.parts.js_for_article')
@endpush

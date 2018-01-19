@extends('layouts.app')

@section('title')
    编辑文章
@endsection

@push('css')
    @include('article.parts.upload_css')
@endpush

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li>
            <a href="/">
                {{ config('app.name') }}
            </a>
        </li>
        <li>
            <a href="/article/{{ $article->id }}">
                {{ $article->title }}
            </a>
        </li>
        <li class="active">
            编辑文章
        </li>
    </ol>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-10">
                {!! Form::open(['method' => 'PUT', 'route' => ['article.update', $article->id], 'class' => 'form-horizontal', 'id'=>'article_form', 'enctype' => "multipart/form-data"]) !!}
                <div class="row">
                    <legend>
                        编辑文章
                    </legend>
                    <div class="btn-group-lg pull-right">
                        <input name="image_url" type="hidden">
                            {!! Form::button("　存　稿　", ['class' => 'btn btn-warning btn-draft']) !!}
                {!! Form::submit("　发　布　", ['class' => 'btn btn-success']) !!}
                        </input>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    {!! Form::label('title', '标题') !!}
            {!! Form::text('title',$article->title, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('title') }}
                    </small>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('category_ids') ? ' has-error' : '' }}">
                            {!! Form::label('category_ids', '分类') !!}
            {!! Form::select('category_ids[]',$categories,$article->categories->pluck('id')->toArray(), ['id' => 'category_ids', 'class' => 'form-control', 'required' => 'required','multiple'=>'multiple']) !!}
                            <small class="text-danger">
                                {{ $errors->first('category_ids') }}
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('is_top') ? ' has-error' : '' }}">
                            {!! Form::label('is_top', '是否上首页') !!}
            {!! Form::select('is_top', [ 0 => '否', 1 => '是'], $article->is_top, ['id' => 'is_top', 'class' => 'form-control', 'required' => 'required']) !!}
                            <small class="text-danger">
                                {{ $errors->first('is_top') }}
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('delay') ? ' has-error' : '' }}">
                            {!! Form::label('delay', '是否延迟发布(如果延迟发布请选择时间)') !!}
                {!! Form::select('delay',[ 
                    0 => '否', 
                    12 => '延迟12小时发布',
                    36 => '延迟一天半(36小时)',
                    48 => '延迟2天发布',
                    72 => '延迟3天发布',
                    96 => '延迟4天发布',
                    120 => '延迟5天发布',
                    144 => '延迟6天发布',
                    168 => '延迟7天发布',
                ], null, ['class' => 'form-control', 'disabled' => $article->delay_time ? true : false]) !!}

                @if($article->delay_time)
                            <p class="small text-info">
                                已启动定时发布于: ({{ $article->delay_time }})
                            </p>
                            @endif
                            <small class="text-danger">
                                {{ $errors->first('delay') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('keywords') ? ' has-error' : '' }}">
                    {!! Form::label('keywords', '关键词(用英文,隔开 或者按Tab键自动隔开关键词)') !!}
            {!! Form::text('keywords', str_replace(' ',',',$article->keywords), ['class' => 'form-control', 'required' => 'required', 'data-role' => 'tagsinput']) !!}
                    <small class="text-danger">
                        {{ $errors->first('keywords') }}
                    </small>
                </div>
                {{--
                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    {!! Form::label('description', '简介') !!}
            {!! Form::textarea('description',$article->description, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">
                        {{ $errors->first('description') }}
                    </small>
                </div>
                --}}
                <div class="form-group{{ $errors->has('body') ? ' has-error-for-editor' : '' }}">
                    {!! Form::label('body', '正文') !!}
         
            {{-- {!! Form::hidden('body',$article->body, ['class' => 'form-control', 'required' => 'required']) !!}
                    <div class="editable">
                    </div>
                    --}}
                    <editor name="body" value="{{ $article->body }}">
                    </editor>
                    <small class="text-danger">
                        {{ $errors->first('body') }}
                    </small>
                </div>
                @include('article.parts.images_selected', ['article_images' => $article->images, 'article' => $article])
                <div class="btn-group-lg pull-right">
                    <input name="user_name" type="hidden" value="{{ Auth::user()->name }}">
                        <input name="user_id" type="hidden" value="{{ Auth::user()->id }}">
                            <input name="image_url" type="hidden" value="{{ $article->image_url }}">
                                <input id="hidden_status" name="status" type="hidden" value="1">
                                    {!! Form::button("　存　稿　", ['class' => 'btn btn-warning btn-draft']) !!}
            {!! Form::submit("　发　布　", ['class' => 'btn btn-success']) !!}
                                </input>
                            </input>
                        </input>
                    </input>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-md-2">
                {{-- @include('article.parts.media_panel') --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link href="/css/simditor.css" rel="stylesheet" type="text/css">
    @endpush
</link>
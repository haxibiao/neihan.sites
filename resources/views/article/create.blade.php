@extends('layouts.app')

@section('title')
    创建文章
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@push('css')
    @include('article.parts.upload_css')
@endpush

@section('content')

<div class="container">
<ol class="breadcrumb">
    <li><a href="/">{{ config('app.name') }}</a></li>
    <li class="active">创建文章</li>
  </ol>

<div class="panel panel-default">
  <div class="panel-body">
    <div class="col-md-10">
    {!! Form::open(['method' => 'POST', 'route' => 'article.store', 'class' => 'form-horizontal', 'enctype' => "multipart/form-data"]) !!}      
      <div class="row">
          <legend>创建文章</legend>
    
            <div class="btn-group-lg pull-right">
                <input type="hidden" name="image_url">
                {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
                {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
            </div>
      </div>
    
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {!! Form::label('title', '标题') !!}
            {!! Form::text('title',null, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('title') }}</small>
        </div>
        
        <div class="row">
            <div class="col-md-6">
            <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                {!! Form::label('category_id', '分类') !!}
                {!! Form::select('category_id',$categories,null, ['id' => 'category_id', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('category_id') }}</small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group{{ $errors->has('author') ? ' has-error' : '' }}">
                {!! Form::label('author', '作者') !!}
                {!! Form::text('author', Auth::user()->name, ['class' => 'form-control', 'readonly' => 'true']) !!}
                <small class="text-danger">{{ $errors->first('author') }}</small>

                <input type="hidden" name="author_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            </div>
            </div>
        </div>

        <div class="form-group{{ $errors->has('keywords') ? ' has-error-for-editor' : '' }}">
            {!! Form::label('keywords', '关键词(用英文,隔开 或者按Tab键自动隔开关键词)') !!}
            <div class="row">
            <div class="col-md-12">
            {!! Form::text('keywords',null, ['class' => 'form-control', 'required' => 'required', 'data-role' => 'tagsinput']) !!}
            </div>
            </div>
            <small class="text-danger">{{ $errors->first('keywords') }}</small>
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', '简介') !!}
            {!! Form::textarea('description',null, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('description') }}</small>
        </div>

        <div class="form-group{{ $errors->has('body') ? ' has-error-for-editor' : '' }}">
            {!! Form::label('body', '正文') !!}
            {!! Form::hidden('body',null, ['class' => 'form-control', 'required' => 'required']) !!}
            <div class="editable"></div>
            <small class="text-danger">{{ $errors->first('body') }}</small>
        </div>

        @include('article.parts.images_selected', ['article_images' => []])
        
        <div class="btn-group pull-right">
            <input type="hidden" name="image_url">
            {!! Form::reset("重置", ['class' => 'btn btn-lg btn-warning']) !!}
            {!! Form::submit("保存", ['class' => 'btn btn-lg btn-success']) !!}
        </div>
    
      {!! Form::close() !!}
  </div>
  <div class="col-md-2">
    @include('article.parts.media_panel')
  </div>
  </div>
</div>


</div>

@endsection

@push('scripts')

@include('article.parts.upload_js')

@include('article.parts.summernote_init')

<script type="text/javascript">

  $(function() {

    // $('.editable').trigger('focus');

    //prevent double click try to create twice ...
    $('input[type="submit"]').click(function() {
        // $('input[type="submit"]').attr('disabled',true);
        window.setTimeout(function(){
                $('input[type="submit"]').attr('disabled',true);
            },100);
        window.setTimeout(function(){
                $('input[type="submit"]').attr('disabled',false);
            },2000);
    });
    
  });

</script>

<style>
    .bootstrap-tagsinput {
        width: 100%;
    }
    #keywords {
        width: 100%;
    }
</style>
@endpush
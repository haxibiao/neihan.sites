@extends('layouts.app')

@section('title')
    编辑文章
@endsection

@section('content')

<div class="container-fluid">
<ol class="breadcrumb">
    <li><a href="/">{{ config('app.name') }}</a></li>
    <li class="active">编辑文章</li>
  </ol>

<div class="panel panel-default">
  <div class="panel-body">
    
  <div class="col-md-7">
    {!! Form::open(['method' => 'PUT', 'route' => ['article.update', $article->id], 'class' => 'form-horizontal', 'enctype' => "multipart/form-data"]) !!}
      <legend>编辑文章</legend>
    
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {!! Form::label('title', '标题') !!}
            {!! Form::text('title',$article->title, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('title') }}</small>
        </div>
        
        <div class="row">
        <div class="col-md-6">
        <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
            {!! Form::label('category_id', '分类') !!}
            {!! Form::select('category_id',$categories,$article->category_id, ['id' => 'category_id', 'class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('category_id') }}</small>
        </div>
        </div>

        <div class="col-md-6">
        <div class="form-group{{ $errors->has('author') ? ' has-error' : '' }}">
            {!! Form::label('author', '作者') !!}
            {!! Form::text('author', $article->author, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('author') }}</small>

            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
        </div>
        </div>
        </div>

        <div class="form-group{{ $errors->has('keywords') ? ' has-error' : '' }}">
            {!! Form::label('keywords', '关键词') !!}
            {!! Form::text('keywords',$article->keywords, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('keywords') }}</small>
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', '简介') !!}
            {!! Form::text('description',$article->description, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('description') }}</small>
        </div>

        <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
            {!! Form::label('body', '正文') !!}
            {!! Form::hidden('body',$article->body, ['class' => 'form-control', 'required' => 'required']) !!}
            <div class="editable"></div>
            <small class="text-danger">{{ $errors->first('body') }}</small>
        </div>
    
        <div class="btn-group pull-right">
            <input type="hidden" name="image_url">
            {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
            {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
        </div>
    
    {!! Form::close() !!}
  </div>
  <div class="col-md-5">
      @include('article.parts.image_upload_ui')
  </div>
  </div>
</div>

</div>

@endsection

@push('scripts')
<script type="text/javascript">
  $(function() {
    var editor = $('.editable').summernote({
        lang: 'zh-CN', // default: 'en-US',
        height: 500,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
      });

    $('.editable').summernote('code',$('input[name="body"]').val());

    $('.editable').on('summernote.change', function(we, contents, $editable) {
      $('input[name="body"]').val(contents);
    });
  });

</script>
@endpush
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
    <li><a href="/">{{ config('app.name') }}</a></li>
    <li><a href="/article/{{ $article->id }}">{{ $article->title }}</a></li>
    <li class="active">编辑文章</li>
  </ol>

<div class="panel panel-default">
  <div class="panel-body">

  <div class="col-md-10">
    {!! Form::open(['method' => 'PUT', 'route' => ['article.update', $article->id], 'class' => 'form-horizontal', 'id'=>'article_form', 'enctype' => "multipart/form-data"]) !!}
      <div class="row">
          <legend>编辑文章</legend>

            <div class="btn-group-lg pull-right">
                <input type="hidden" name="image_url">
                {!! Form::button("　存　稿　", ['class' => 'btn btn-warning btn-draft']) !!}
                {!! Form::submit("　发　布　", ['class' => 'btn btn-success']) !!}
            </div>
      </div>

        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {!! Form::label('title', '标题') !!}
            {!! Form::text('title',$article->title, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('title') }}</small>
        </div>

        <div class="row">
        <div class="col-md-6">
        <div class="form-group{{ $errors->has('category_ids') ? ' has-error' : '' }}">
            {!! Form::label('category_ids', '分类') !!}
            {!! Form::select('category_ids[]',$categories,$article->categories->pluck('id')->toArray(), ['id' => 'category_ids', 'class' => 'form-control', 'required' => 'required','multiple'=>'multiple']) !!}
            <small class="text-danger">{{ $errors->first('category_ids') }}</small>
        </div>
        </div>

        <div class="col-md-3">
        <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
            {!! Form::label('user_name', '最后编辑者') !!}
            {!! Form::text('user_name', Auth::user()->name, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('user_name') }}</small>

            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
        </div>
        </div>

        <div class="col-md-3">
        <div class="form-group{{ $errors->has('is_top') ? ' has-error' : '' }}">
            {!! Form::label('is_top', '是否上首页') !!}
            {!! Form::select('is_top', [ 0 => '否', 1 => '是'], $article->is_top, ['id' => 'is_top', 'class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('is_top') }}</small>
        </div>
        </div>
        </div>

        <div class="form-group{{ $errors->has('keywords') ? ' has-error' : '' }}">
            {!! Form::label('keywords', '关键词(用英文,隔开 或者按Tab键自动隔开关键词)') !!}
            {!! Form::text('keywords', str_replace(' ',',',$article->keywords), ['class' => 'form-control', 'required' => 'required', 'data-role' => 'tagsinput']) !!}
            <small class="text-danger">{{ $errors->first('keywords') }}</small>
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', '简介') !!}
            {!! Form::textarea('description',$article->description, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('description') }}</small>
        </div>

        <div class="form-group{{ $errors->has('body') ? ' has-error-for-editor' : '' }}">
            {!! Form::label('body', '正文') !!}
            {!! Form::hidden('body',$article->body, ['class' => 'form-control', 'required' => 'required']) !!}
            <div class="editable"></div>
            <small class="text-danger">{{ $errors->first('body') }}</small>
        </div>

        @include('article.parts.images_selected', ['article_images' => $article->images, 'article' => $article])

        <div class="btn-group-lg pull-right">
            <input type="hidden" name="image_url" value="{{ $article->image_url }}">
            <input type="hidden" name="status" id="hidden_status" value="1">
            {!! Form::button("　存　稿　", ['class' => 'btn btn-warning btn-draft']) !!}
            {!! Form::submit("　发　布　", ['class' => 'btn btn-success']) !!}
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

    $('.btn-draft').click(function() {
        $('#hidden_status').val(0);
        $('#article_form').submit();
    });

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
            ['height', ['height']],
            ["insert", ["link","hr"]],
            ['misc',['codeview', 'undo','redo','fullscreen']]
          ]
      });

    $('.editable').summernote('code',$('input[name="body"]').val());

    $('.editable').on('summernote.change', function(we, contents, $editable) {
      $('input[name="body"]').val(contents);
    });
  });

</script>
@endpush
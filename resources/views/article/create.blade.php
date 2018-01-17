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
    {!! Form::open(['method' => 'POST', 'route' => 'article.store', 'class' => 'form-horizontal', 'id'=>'article_form', 'enctype' => "multipart/form-data"]) !!}
      <div class="row">
          <legend>创建文章</legend>

            <div class="btn-group-lg pull-right">
                <input type="hidden" name="image_url">
                {!! Form::button(" 存 稿 ", ['class' => 'btn btn-warning btn-draft']) !!}
                {!! Form::submit(" 发 布 ", ['class' => 'btn btn-success']) !!}
            </div>
      </div>

        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {!! Form::label('title', '标题') !!}
            {!! Form::text('title',null, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('title') }}</small>
        </div>

        <div class="row">
            <div class="col-md-6">
            <div class="form-group{{ $errors->has('category_ids') ? ' has-error' : '' }}">
                {!! Form::label('category_ids', '分类(多选会默认第一个为主分类)') !!}
                {!! Form::select('category_ids[]',$categories,null, ['id' => 'category_ids', 'class' => 'form-control', 'required' => 'required','multiple'=>'multiple']) !!}
                <small class="text-danger">{{ $errors->first('category_ids') }}</small>
            </div>
            </div>
            <div class="col-md-3">
            <div class="form-group{{ $errors->has('author') ? ' has-error' : '' }}">
                {!! Form::label('author', '作者') !!}
                {!! Form::text('author', Auth::user()->name, ['class' => 'form-control', 'readonly' => 'true']) !!}
                <small class="text-danger">{{ $errors->first('author') }}</small>

                <input type="hidden" name="author_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="user_name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            </div>
            </div>

            <div class="col-md-3">
            <div class="form-group{{ $errors->has('is_top') ? ' has-error' : '' }}">
                {!! Form::label('is_top', '是否上首页') !!}
                {!! Form::select('is_top',[ 0 => '否', 1 => '是'], null, ['id' => 'is_top', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('is_top') }}</small>
            </div>
            </div>

            <div class="col-md-3">
            <div class="form-group{{ $errors->has('is_Delay') ? ' has-error' : '' }}">
                {!! Form::label('is_Delay', '是否延迟发布(如果延迟发布请选择时间)') !!}
                {!! Form::select('is_Delay',[ 
                    0 => '否', 
                    12 => '延迟12小时发布',
                    36 => '延迟一天半(36小时)',
                    48 => '延迟2天发布',
                    72 => '延迟3天发布',
                    96 => '延迟4天发布',
                    120 => '延迟5天发布',
                    144 => '延迟6天发布',
                    168 => '延迟7天发布',
                ], null, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('is_Delay') }}</small>
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

   {{--      <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', '简介') !!}
            {!! Form::textarea('description',null, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('description') }}</small>
        </div> --}}


        <div class="form-group{{ $errors->has('body') ? ' has-error-for-editor' : '' }}">
            {!! Form::label('body', '正文') !!}

            {{-- {!! Form::hidden('body',null, ['class' => 'form-control', 'required' => 'required']) !!}             --}}
            {{-- <div class="editable"></div> --}}

            <editor name="body"></editor>

            <small class="text-danger">{{ $errors->first('body') }}</small>
        </div>

        <image-select></image-select>

        {{-- @include('article.parts.images_selected', ['article_images' => []]) --}}

        <div class="btn-group-lg pull-right">
            <input type="hidden" name="image_url">
            <input type="hidden" name="status" id="hidden_status" value="1">
            {!! Form::button("　存　稿　", ['class' => 'btn btn-warning btn-draft','id'=>'draftBtn']) !!}
            {!! Form::submit("　发　布　", ['class' => 'btn btn-success']) !!}
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
    <link rel="stylesheet" type="text/css" href="/css/simditor.css">
@endpush

@push('scripts')

<script type="text/javascript">

  $(function() {

    $('.btn-draft').click(function() {
        $('#hidden_status').val(0);
        $('#article_form').submit();
    });

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
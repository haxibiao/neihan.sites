@extends('layouts.app')

@section('title')
    创建文章
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

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

        <div class="form-group{{ $errors->has('keywords') ? ' has-error' : '' }}">
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

        <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
            {!! Form::label('body', '正文') !!}
            {!! Form::hidden('body',null, ['class' => 'form-control', 'required' => 'required']) !!}
            <div class="editable"></div>
            <small class="text-danger">{{ $errors->first('body') }}</small>
        </div>

        {{-- <div class="form-group{{ $errors->has('is_top') ? ' has-error' : '' }}">
            {!! Form::label('is_top', '是否上首页滚动(上需要比900*500大的主要配图，程序自动裁剪)') !!}
            {!! Form::select('is_top', [0 => '不上', 1 => '上'], null, ['id' => 'is_top', 'class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('is_top') }}</small>
        </div>

        <div class="form-group{{ $errors->has('image_top') ? ' has-error' : '' }}">
            {!! Form::label('image_top', '首页滚动配图') !!}
            {!! Form::file('image_top', []) !!}
            <p class="help-block">不上首页滚动的无需配这个图</p>
            <small class="text-danger">{{ $errors->first('image_top') }}</small>
        </div> --}}

        @include('article.parts.article_images_selected', ['article_images' => []])
        
        <div class="btn-group pull-right">
            <input type="hidden" name="image_url">
            {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
            {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
        </div>
    
      {!! Form::close() !!}
  </div>
  <div class="col-md-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">多媒体</h3>
        </div>
        <div class="panel-body">
            <ul class="list-group">
                <li class="list-group-item">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-pic-modal-lg">加入图片</button>
                    <div class="modal fade bs-pic-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">     
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">加入图片</h4>
                              </div>                     
                            <div class="col-md-12">
                                @include('article.parts.image_add')
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                {{-- <button type="button" class="btn btn-primary">确定</button> --}}
                            </div>
                        </div>
                      </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-video-modal-lg">加入视频</button>
                    <div class="modal fade bs-video-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">     
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">加入视频</h4>
                              </div>                     
                            <div class="col-md-12">
                                @include('article.parts.video_add')
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                {{-- <button type="button" class="btn btn-primary">确定</button> --}}
                            </div>
                        </div>
                      </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
  </div>
  </div>
</div>


</div>

@endsection

@push('scripts')

<link rel="stylesheet" href="/css/jquery.tagsinput.css">
<script src="/js/jquery.tagsinput.js"></script>


<script type="text/javascript">

  $(function() {

    // $('.editable').trigger('focus');

    $('#keywords').tagsInput({
        width:'auto',
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
            ['height', ['height']]
          ],
        focus:true
      });

    $('.editable').summernote('code',$('input[name="body"]').val());

    $('.editable').on('summernote.change', function(we, contents, $editable) {
      $('input[name="body"]').val(contents);
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
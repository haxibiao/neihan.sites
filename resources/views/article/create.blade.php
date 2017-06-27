@extends('layouts.app')

@section('title')
    添加文章
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@section('content')

<div class="container">
<ol class="breadcrumb">
    <li><a href="/">懂点医</a></li>
    <li class="active">添加文章</li>
  </ol>

<form action="/article" method="POST" role="form" enctype="multipart/form-data" id='article_form'>
  {{ csrf_field() }}
  <legend>添加新的文章</legend>

  <div class="form-group">
    <label>标题</label>
    <input type="text" class="form-control" placeholder="标题" name="title">
  </div>
  <div class="form-group">
    <label>作者</label>
    <input type="text" class="form-control" placeholder="作者" name="author" value="{{ Auth::user()->name }}">
  </div>
  <div class="form-group">
    <label>关键词</label>
    <input type="text" class="form-control" placeholder="关键词" name="keywords">
  </div>
  <div class="form-group">
    <label>简介</label>
    <input type="text" class="form-control" placeholder="简介" name="description">
  </div>
  <div class="form-group">
    <label>配图</label>
    <div>
      <input type="file" name="image[]">
      <button type="button" class="btn btn-default" id="add_image">+</button>
      <button type="button" class="btn btn-danger" id="upload_image">上传</button>
    </div>
    <div class="row" id="preview_images">
        
    </div>
  </div>
  <div class="form-group">
    <label>正文</label>
    <textarea class="hidden"  name="body"></textarea>
    <div class="editable"></div>
  </div>

  <div class="form-group">
    <button type="submit" class="btn btn-danger">提交</button>
  </div>

  
</form>

</div>

@endsection

@push('scripts')
<script type="text/javascript">
  $(function() {
    $('#add_image').click(function() {
        $('<input type="file" name="image[]">').insertBefore($(this));
    });

    $('#upload_image').click(function() {
        var form = $('#article_form')[0];
        var data = new FormData(form);
        console.log(data);
        console.log(form);
        $.ajax({
          url: '/upload-image',
          type: 'POST',
          cache: false,
          data: data,
          processData: false,
          contentType: false
        }).done(function(res) {
          $('#preview_images').innerHTML = '';
          for(var i in res) {
            var img_path = res[i];
            $('#preview_images').append('<img src="/'+img_path+'" class="col-sm-3 img img-responsive" onclick="select_image(this)"/>')
          }
        }).fail(function(res) {
            
        });
    });
  });

  var select_image = function(img) {
    var img_url = $(img).attr('src');
    $('.editable').summernote('insertImage', img_url,function ($image) {
      $image.attr('data-url-big', img_url.replace('small.jpg',''));
    });

  }

  $('.editable').on('summernote.change', function(we, contents, $editable) {
    $('textarea').val(contents);
  });

</script>
@endpush
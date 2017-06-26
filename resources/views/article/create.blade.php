@extends('layouts.app')

@section('title')
    添加文章
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@section('body')

<div class="container">
<form action="/article" method="POST" role="form">
  {{ csrf_field() }}
  <legend>添加新的文章</legend>

  <div class="form-group">
    <label>标题</label>
    <input type="text" class="form-control" placeholder="标题" name="title">
  </div>
  <div class="form-group">
    <label>作者</label>
    <input type="text" class="form-control" placeholder="作者" name="author">
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
    <label>正文</label>
    <textarea rows="10" class="form-control"  name="body"></textarea>
  </div>

  <div class="form-group">
    <button type="submit" class="btn btn-danger">提交</button>
  </div>

  
</form>

</div>

@endsection
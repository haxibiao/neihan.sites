@extends('layouts.app')

@section('title')
    专题管理
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@section('content')

<div class="container">
      <div class="panel panel-default">
        <div class="panel-heading category-admin-header">
          <div class="pull-right">
            <a class="btn btn-primary" href="/category/create{{ Request::get('type') ? '?type='.Request::get('type') : '' }}" role="button">添加专题</a>
          </div>
          <h3 class="panel-title category-admin-title">专题管理</h3>
          <basic-search api="/category/list?q="></basic-search>
        </div>
        <div class="panel-body small">
          @if(!empty($accurateCategory))
            @foreach($accurateCategory->chunk(3) as $category_group)
              <div class="row">
                @each('category.parts.list_item',$category_group,'category')
              </div>
            @endforeach
          @endif
          @foreach($categories->chunk(3) as $category_group)
            <div class="row">
              @each('category.parts.list_item',$category_group,'category')
            </div>
          @endforeach
        </div>
        <div class="panel-footer">
          {!! $categories->links() !!}
        </div>
      </div>  
  

</div>

@endsection
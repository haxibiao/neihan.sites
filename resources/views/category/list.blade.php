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
          @foreach($categories->chunk(3) as $category_group)
            <div class="row">
              @foreach ($category_group as $category)
                  <div class=" category-item top10 col-xs-12 clearfix">
                     <img src="{{ $category->logo() }}" alt="" class="pull-left">
                    <div class="pull-right">

                      {!! Form::open(['method' => 'get', 'route' => ['category.edit', $category->id], 'class' => 'form-horizontal']) !!}
                          <div class="btn-group pull-right right10">
                              {!! Form::submit("编辑", ['class' => 'btn btn-sm btn-primary']) !!}
                          </div>        
                      {!! Form::close() !!}
                    </div>
                    <div class="pull-right">
                        
                        {!! Form::open(['method' => 'delete', 'route' => ['category.destroy', $category->id], 'class' => 'form-horizontal']) !!}
                            <div class="btn-group pull-right" style="padding-right: 5px">
                                {!! Form::submit("删除", ['class' => 'btn btn-sm btn-default']) !!}
                            </div>    
                        {!! Form::close() !!} 
                      </div>   
                      
                     
                      <div class="item-info">
                        <h5 class="category-name">{{ $category->name }} ({{ $category->name_en }})</h5>
                        <span>创建人: {{ $category->user->name }} </span>
                        <p style="min-height: 100px" class="small well">{{ $category->description }}</p>
                      </div>
                  </div>
              @endforeach
            </div>
          @endforeach
        </div>
        <div class="panel-footer">
          {!! $categories->links() !!}
        </div>
      </div>  
  

</div>

@endsection
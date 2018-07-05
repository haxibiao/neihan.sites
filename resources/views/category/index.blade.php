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
        <div class="panel-heading">
          <div class="pull-right">
            <a class="btn btn-primary" href="/category/create{{ Request::get('type') ? '?type='.Request::get('type') : '' }}" role="button">添加分类</a>
          </div>
          <h3 class="panel-title" style="min-height: 50px">专题管理</h3>
        </div>
        <div class="panel-body small">
          @foreach($categories->chunk(3) as $category_group)
            <div class="row">
              @foreach ($category_group as $category)
                  <div class="top10 col-xs-6 col-md-4 col-lg-3">
                    <div class="pull-right">

                      {!! Form::open(['method' => 'get', 'route' => ['category.edit', $category->id], 'class' => 'form-horizontal']) !!}
                          <div class="btn-group pull-right right10">
                              {!! Form::submit("编辑", ['class' => 'btn btn-sm btn-primary']) !!}
                          </div>        
                      {!! Form::close() !!}

                    </div>   
                      <h5>{{ $category->name }} ({{ $category->name_en }})</h5>
                      <img src="{{ $category->logo() }}" alt="">
                      <div class="pull-right">
                        
                        {!! Form::open(['method' => 'delete', 'route' => ['category.destroy', $category->id], 'class' => 'form-horizontal']) !!}
                            <div class="btn-group pull-right">
                                {!! Form::submit("删除", ['class' => 'btn btn-sm btn-danger']) !!}
                            </div>    
                        {!! Form::close() !!} 
                      </div>
                      <p>创建人: {{ $category->user->name }} </p>
                      <p style="min-height: 100px" class="small well">{{ $category->description }}</p>
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
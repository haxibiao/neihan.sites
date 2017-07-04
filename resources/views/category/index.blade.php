@extends('layouts.app')

@section('title')
    分类列表
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@section('content')

<div class="container">
  <ol class="breadcrumb">
    <li><a href="/">{{ config('app.name') }}</a></li>
    <li class="active">分类列表</li>
  </ol>

  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a class="btn btn-primary" href="/category/create" role="button">添加分类</a>
      </div>
      <h3 class="panel-title" style="min-height: 50px">分类列表</h3>
    </div>
    <div class="panel-body">
      @foreach($categories as $id => $category)
        @if($category)
        <div class="top10">
          <a href="/category/{{ $category->id }}" class="list-group-item left{{ $category->level*10 }}">
            {!! Form::open(['method' => 'delete', 'route' => ['category.destroy', $category->id], 'class' => 'form-horizontal']) !!}
                <div class="btn-group pull-right">
                    {!! Form::submit("删除", ['class' => 'btn btn-sm btn-danger']) !!}
                </div>    
            {!! Form::close() !!}

            {!! Form::open(['method' => 'get', 'route' => ['category.edit', $category->id], 'class' => 'form-horizontal']) !!}
                <div class="btn-group pull-right right10">
                    {!! Form::submit("编辑", ['class' => 'btn btn-sm btn-warning']) !!}
                </div>        
            {!! Form::close() !!}
            <h4 class="list-group-item-heading">{{ $category->name }} ({{ $category->name_en }})</h4>
            {{-- <p class="list-group-item-text"> {{ $category->description }} {{ $category->created_at->diffForHumans() }} 
            @if($category->user)
              - {{ $category->user->name }}
            @endif
            </p> --}}
          </a>
          {{-- <a class="btn btn-sm btn-warning" href="/category/{{ $category->id }}/edit" role="button">编辑</a>  --}}
        </div>
        @endif
      @endforeach
    </div>
  </div>  

</div>

@endsection
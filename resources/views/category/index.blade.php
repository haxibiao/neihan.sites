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
    <li><a href="/">懂点医</a></li>
    <li class="active">分类列表</li>
  </ol>

  @foreach($categories as $category)
    <div class="row top10">
      <a href="/category/{{ $category->id }}" class="list-group-item">
        {!! Form::open(['method' => 'delete', 'route' => ['category.destroy', $category->id], 'class' => 'form-horizontal']) !!}
            <div class="btn-group pull-right">
                {!! Form::submit("删除", ['class' => 'btn btn-danger']) !!}
            </div>    
        {!! Form::close() !!}

        {!! Form::open(['method' => 'get', 'route' => ['category.edit', $category->id], 'class' => 'form-horizontal']) !!}
            <div class="btn-group pull-right right10">
                {!! Form::submit("编辑", ['class' => 'btn btn-warning']) !!}
            </div>        
        {!! Form::close() !!}
        <h4 class="list-group-item-heading">{{ $category->name }} ({{ $category->name_en }})</h4>
        <p class="list-group-item-text"> {{ $category->description }} {{ $category->created_at->diffForHumans() }} 
        @if($category->user)
          - {{ $category->user->name }}
        @endif
        </p>
      </a>
      {{-- <a class="btn btn-warning" href="/category/{{ $category->id }}/edit" role="button">编辑</a>  --}}
    </div>
  @endforeach

</div>

@endsection
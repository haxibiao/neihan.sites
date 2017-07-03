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
  <div class="list-group">
    <a href="/category/{{ $category->name_en }}" class="list-group-item active">
      <h4 class="list-group-item-heading">{{ $category->name }} ({{ $category->name_en }})</h4>
      <p class="list-group-item-text"> {{ $category->description }} {{ $category->created_at->diffForHumans() }} 
      @if($category->user)
        - {{ $category->user->name }}
      @endif
      </p>
    </a>

  </div>
  @endforeach

</div>

@endsection
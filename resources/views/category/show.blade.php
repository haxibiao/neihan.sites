@extends('layouts.app')

@section('content')
	<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">懂点医</a></li>
        <li><a href="/category">分类列表</a></li>
        <li class="active">{{ $category->name }}</li>
      </ol>
		<h1>{{ $category->name }} ({{ $category->name_en }})</h1>
		<p class="list-group-item-text"> {{ $category->description }} {{ $category->created_at->diffForHumans() }} - {{ $category->user->name }}</p>
	</div>
@stop